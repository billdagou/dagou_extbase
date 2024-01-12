<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class IntegerValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (filter_var($value, FILTER_VALIDATE_INT) === FALSE) {
            $this->addError('integer', 1221560494);
        }
    }
}