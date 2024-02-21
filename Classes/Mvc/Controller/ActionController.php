<?php
namespace Dagou\DagouExtbase\Mvc\Controller;

use Dagou\DagouExtbase\Property\TypeConverter\UploadedFileReferenceConverter;
use Dagou\DagouExtbase\Traits\Inject\ExtensionService;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class ActionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
    use ExtensionService;

    /**
     * @param string $extensionName
     * @param string $pluginName
     * @param string $messageBody
     * @param string $messageTitle
     * @param \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity $severity
     * @param bool $storeInSession
     *
     * @throws \TYPO3\CMS\Core\Exception
     */
    public function addExternalFlashMessage(string $extensionName, string $pluginName, string $messageBody, string $messageTitle = '', ContextualFeedbackSeverity $severity = ContextualFeedbackSeverity::OK, bool $storeInSession = TRUE) {
        $flashMessage = GeneralUtility::makeInstance(FlashMessage::class, $messageBody, $messageTitle, $severity, $storeInSession);

        $this->getFlashMessageQueue('extbase.flashmessages.'.$this->extensionService->getPluginNamespace($extensionName, $pluginName))
            ->enqueue($flashMessage);
    }

    /**
     * @return bool
     */
    protected function getErrorFlashMessage(): bool {
        return FALSE;
    }

    /**
     * @param string $argumentName
     * @param string $propertyName
     * @param array $overrideConfiguration
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\NoSuchArgumentException
     */
    protected function setTypeConverterConfigurationForFileUpload(string $argumentName, string $propertyName, array $overrideConfiguration = []) {
        $configuration = [
            UploadedFileReferenceConverter::CONFIGURATION_ALLOWED_FILE_EXTENSIONS => $overrideConfiguration['ext'] ?? $this->settings[$propertyName]['ext'] ?? $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
            UploadedFileReferenceConverter::CONFIGURATION_MAX_UPLOAD_FILE_SIZE => $overrideConfiguration['size'] ?? $this->settings[$propertyName]['size'] ?? NULL,
            UploadedFileReferenceConverter::CONFIGURATION_RENAME => $overrideConfiguration['rename'] ?? NULL,
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_CONFLICT_MODE => $overrideConfiguration['conflict'] ?? $this->settings[$propertyName]['conflict'] ?? NULL,
            UploadedFileReferenceConverter::CONFIGURATION_UPLOAD_FOLDER => $overrideConfiguration['folder'] ?? $this->settings[$propertyName]['folder'] ?? NULL,
        ];

        $this->arguments->getArgument($argumentName)
            ->getPropertyMappingConfiguration()
                ->forProperty($propertyName)
                    ->setTypeConverterOptions(UploadedFileReferenceConverter::class, $configuration);
    }
}