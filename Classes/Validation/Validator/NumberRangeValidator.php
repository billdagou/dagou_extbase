<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class NumberRangeValidator extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'minimum' => [0, 'The minimum value to accept', 'integer'],
        'maximum' => [PHP_INT_MAX, 'The maximum value to accept', 'integer'],
    ];

    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (!is_numeric($value)) {
            $this->addError('numberRange', 1221563685);

            return;
        }

        if ($value < $this->options['minimum'] || $value > $this->options['maximum']) {
            $this->addError('numberRange', 1221561046, [$this->options['minimum'], $this->options['maximum']]);
        }
    }
}