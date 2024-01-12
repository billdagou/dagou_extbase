<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class EmailAddressValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (!is_string($value) || !GeneralUtility::validEmail($value)) {
            $this->addError('emailAddress', 1221559976);
        }
    }
}