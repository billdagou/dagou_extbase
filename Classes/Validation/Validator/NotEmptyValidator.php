<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class NotEmptyValidator extends AbstractValidator {
    /**
     * @var bool
     */
    protected $acceptsEmptyValues = FALSE;

    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if ($value === NULL) {
            $this->addError('notEmpty', 1221560910);
        }

        if ($value === '') {
            $this->addError('notEmpty', 1221560718);
        }

        if (is_array($value) && empty($value)) {
            $this->addError('notEmpty', 1347992400);
        }

        if ($value instanceof \Countable && $value->count() === 0) {
            $this->addError('notEmpty', 1347992453);
        }
    }
}