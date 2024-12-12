<?php
namespace Dagou\DagouExtbase\Property\TypeConverter;

use Dagou\DagouExtbase\Property\Exception\FileSizeTooLargeException;
use Dagou\DagouExtbase\Property\Exception\InvalidFileExtensionException;
use Dagou\DagouExtbase\Traits\Inject\HashServiceTrait;
use Dagou\DagouExtbase\Traits\Inject\PersistenceManagerTrait;
use Dagou\DagouExtbase\Traits\Inject\ResourceFactoryTrait;
use TYPO3\CMS\Core\Resource\DuplicationBehavior;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Security\FileNameValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;

class UploadedFileReferenceConverter extends AbstractTypeConverter {
    use HashServiceTrait, PersistenceManagerTrait, ResourceFactoryTrait;

    public const CONFIGURATION_ALLOWED_FILE_EXTENSIONS = 'extensions';
    public const CONFIGURATION_MAX_UPLOAD_FILE_SIZE = 'size';
    public const CONFIGURATION_RENAME = 'rename';
    public const CONFIGURATION_UPLOAD_CONFLICT_MODE = 'conflict';
    public const CONFIGURATION_UPLOAD_FOLDER = 'folder';

    protected array $fileReferences = [];
    protected string $defaultUploadFolder = '1:/user_upload/';
    protected string $defaultConflictMode = DuplicationBehavior::RENAME;

    /**
     * @param mixed $source
     * @param string $targetType
     *
     * @return bool
     */
    public function canConvertFrom($source, string $targetType): bool {
        if (!is_subclass_of($targetType, ExtbaseFileReference::class)) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface|null $configuration
     *
     * @return object|null
     * @throws \TYPO3\CMS\Core\Resource\Exception\FileDoesNotExistException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     * @throws \TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException
     * @throws \TYPO3\CMS\Extbase\Security\Exception\InvalidHashException
     */
    public function convertFrom($source, string $targetType, array $convertedChildProperties = [], ?PropertyMappingConfigurationInterface $configuration = NULL): ?object {
        if (!isset($source['error']) || $source['error'] === UPLOAD_ERR_NO_FILE) {
            if ($source['__resource'] ?? FALSE) {
                $resource = $this->hashService->validateAndStripHmac($source['__resource']);

                return $this->createExtbaseFileReferenceFromFile(
                    $this->resourceFactory->getFileObject($resource)
                );
            } elseif ($source['__identity'] ?? FALSE) {
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
    protected function createExtbaseFileReferenceFromFile(File $file, int $identity = NULL): ExtbaseFileReference {
        $fileReference = $this->resourceFactory->createFileReferenceObject(
            [
                'uid_local' => $file->getUid(),
                'uid_foreign' => uniqid('NEW_'),
                'uid' => uniqid('NEW_'),
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
    protected function createExtbaseFileReferenceFromFileReference(FileReference $fileReference, int $identity = NULL): ExtbaseFileReference {
        if ($identity === NULL) {
            $extbaseFileReference = new ExtbaseFileReference();
        } else {
            $extbaseFileReference = $this->persistenceManager->getObjectByIdentifier($identity, ExtbaseFileReference::class);
        }

        $extbaseFileReference->setOriginalResource($fileReference);

        return $extbaseFileReference;
    }

    /**
     * @param array $file
     * @param \TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface $configuration
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     * @throws \Dagou\DagouExtbase\Property\Exception\FileSizeTooLargeException
     * @throws \Dagou\DagouExtbase\Property\Exception\InvalidFileExtensionException
     * @throws \TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException
     * @throws \TYPO3\CMS\Extbase\Security\Exception\InvalidArgumentForHashGenerationException
     * @throws \TYPO3\CMS\Extbase\Security\Exception\InvalidHashException
     */
    protected function createFileReferenceFromUploadedFile(array $file, PropertyMappingConfigurationInterface $configuration): ExtbaseFileReference {
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
            isset($file['__identity']) ? $this->hashService->validateAndStripHmac($file['__identity']) : NULL
        );
    }
}