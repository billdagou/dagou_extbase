<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class NumberValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (!is_numeric($value)) {
            $this->addError('number', 1221563685);
        }
    }
}