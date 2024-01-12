<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class FloatValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (is_float($value)) {
            return;
        }

        if (!is_string($value) || !str_contains($value, '.') || preg_match('/^[0-9.e+-]+$/', $value) !== 1) {
            $this->addError('float', 1221560288);
        }
    }
}