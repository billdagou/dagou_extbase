<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class StringValidator extends AbstractValidator {
    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (!is_string($value)) {
            $this->addError('string', 1238108067);
        }
    }
}