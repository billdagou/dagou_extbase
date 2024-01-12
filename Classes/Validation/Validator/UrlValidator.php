<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class UrlValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (!is_string($value) || !GeneralUtility::isValidUrl($value)) {
            $this->addError('url', 1238108078);
        }
    }
}