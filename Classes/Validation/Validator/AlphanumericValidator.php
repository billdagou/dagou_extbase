<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class AlphanumericValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (!is_string($value) || preg_match('/^[\pL\d]*$/u', $value) !== 1) {
            $this->addError('alphanumeric', 1221551320);
        }
    }
}