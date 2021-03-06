<?php
namespace Dagou\DagouExtbase\Property\TypeConverter;

use Dagou\DagouExtbase\Property\Exception\FileSizeTooLargeException;
use Dagou\DagouExtbase\Property\Exception\InvalidFileExtensionException;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Annotation\Inject;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtBaseFileReference;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\CMS\Extbase\Security\Cryptography\HashService;

class UploadedFileReferenceConverter extends AbstractTypeConverter {
    const CONFIGURATION_ALLOWED_FILE_EXTENSIONS = 'extensions';
    const CONFIGURATION_MAX_UPLOAD_FILE_SIZE = 'size';
    const CONFIGURATION_RENAME = 'rename';
    const CONFIGURATION_UPLOAD_CONFLICT_MODE = 'conflict';
    const CONFIGURATION_UPLOAD_FOLDER = 'folder';
    /**
     * @var array
     */
    protected $sourceTypes = ['array'];
    /**
     * @var string
     */
    protected $targetType = ExtBaseFileReference::class;
    /**
     * @var int
     */
    protected $priority = 30;
    /**
     * @var array
     */
    protected $fileReferences = [];
    /**
     * @var string
     */
    protected $defaultUploadFolder = '1:/user_upload/';
    /**
     * @var string
     */
    protected $defaultConflictMode = DuplicationBehavior::RENAME;
    /**
     * @var \TYPO3\CMS\Extbase\Security\Cryptography\HashService
     */
    protected $hashService;
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;
    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     */
    protected $resourceFactory;

    /**
     * @param \TYPO3\CMS\Extbase\Security\Cryptography\HashService $hashService
     */
    public function injectHashService(HashService $hashService) {
        $this->hashService = $hashService;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager) {
        $this->persistenceManager = $persistenceManager;
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory
     */
    public function injectResourceFactory(ResourceFactory $resourceFactory) {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface|NULL $configuration
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|\TYPO3\CMS\Extbase\Error\Error|NULL
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = NULL) {
        if (!isset($source['error']) || $source['error'] === UPLOAD_ERR_NO_FILE) {
            if ($source['__resource']) {
                $resource = $this->hashService->validateAndStripHmac($source['__resource']);

                return $this->createExtbaseFileReferenceFromFile(
                    $this->resourceFactory->getFileObject($resource)
                );
            } elseif ($source['__identity']) {
                $identity = $this->hashService->validateAndStripHmac($source['__identity']);

                return $this->createExtbaseFileReferenceFromFileReference(
                    $this->resourceFactory->getFileReferenceObject($identity),
                    $identity
                );
            }

            return NULL;
        }

        if ($source['error'] !== UPLOAD_ERR_OK) {
            return new Error('fail', 1575437967);
        }

        if (isset($this->fileReferences[$source['tmp_name']])) {
            return $this->fileReferences[$source['tmp_name']];
        }

        try {
            $fileReference = $this->createFileReferenceFromUploadedFile($source, $configuration);
        } catch (\Exception $e) {
            return new Error($e->getMessage(), $e->getCode());
        }

        $this->fileReferences[$source['tmp_name']] = $fileReference;

        return $fileReference;
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\File $file
     * @param int|NULL $identity
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected function createExtbaseFileReferenceFromFile(File $file, int $identity = NULL): ExtBaseFileReference {
        $fileReference = $this->resourceFactory->createFileReferenceObject(
            [
                'uid_local' => $file->getUid(),
                'uid_foreign' => uniqid('NEW_'),
                'uid' => uniqid('NEW_'),
                'crop' => NULL,
            ]
        );

        return $this->createExtbaseFileReferenceFromFileReference($fileReference, $identity);
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileReference $fileReference
     * @param int|NULL $identity
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected function createExtbaseFileReferenceFromFileReference(FileReference $fileReference, int $identity = NULL): ExtBaseFileReference {
        if ($identity === NULL) {
            $extbaseFileReference = $this->objectManager->get(ExtBaseFileReference::class);
        } else {
            $extbaseFileReference = $this->persistenceManager->getObjectByIdentifier($identity, ExtBaseFileReference::class);
        }

        $extbaseFileReference->setOriginalResource($fileReference);

        return $extbaseFileReference;
    }

    /**
     * @param array $file
     * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected function createFileReferenceFromUploadedFile(array $file, PropertyMappingConfigurationInterface $configuration): ExtBaseFileReference {
        if (!GeneralUtility::makeInstance(FileNameValidator::class)->isValid($file['name'])) {
            throw new InvalidFileExtensionException('extension', 1575438957);
        }

        $allowedFileExtensions = strtolower(
            $configuration->getConfigurationValue(__CLASS__, self::CONFIGURATION_ALLOWED_FILE_EXTENSIONS)
        );
        if ($allowedFileExtensions !== NULL) {
            $fileExtension = strtolower(
                PathUtility::pathinfo($file['name'], PATHINFO_EXTENSION)
            );

            if (!GeneralUtility::inList($allowedFileExtensions, $fileExtension)) {
                throw new InvalidFileExtensionException('extension', 1575439268);
            }
        }

        $maxUploadFileSize = $configuration->getConfigurationValue(__CLASS__, self::CONFIGURATION_MAX_UPLOAD_FILE_SIZE);
        if ($maxUploadFileSize !== NULL) {
            if ($file['size'] > GeneralUtility::getBytesFromSizeMeasurement($maxUploadFileSize)) {
                throw new FileSizeTooLargeException('size', 1575439957);
            }
        }

        $rename = $configuration->getConfigurationValue(__CLASS__, self::CONFIGURATION_RENAME);
        if ($rename !== NULL && is_callable($rename)) {
            $file['name'] = $rename($file['name']);
        }

        $uploadFolder = $configuration->getConfigurationValue(__CLASS__, self::CONFIGURATION_UPLOAD_FOLDER) ?:
            $this->defaultUploadFolder;
        $conflictMode = $configuration->getConfigurationValue(__CLASS__, self::CONFIGURATION_UPLOAD_CONFLICT_MODE) ?:
            $this->defaultConflictMode;

        $uploadedFile = $this->resourceFactory->retrieveFileOrFolderObject($uploadFolder)
            ->addUploadedFile($file, $conflictMode);

        return $this->createExtbaseFileReferenceFromFile(
            $uploadedFile,
            $file['__identity'] ? $this->hashService->validateAndStripHmac($file['__identity']) : NULL
        );
    }
}