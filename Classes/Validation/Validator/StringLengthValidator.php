<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class StringLengthValidator extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'minimum' => [0, 'Minimum length for a valid string', 'integer'],
        'maximum' => [PHP_INT_MAX, 'Maximum length for a valid string', 'integer'],
    ];

    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if (is_object($value)) {
            if (!method_exists($value, '__toString')) {
                $this->addError('The given object could not be converted to a string.', 1238110957);

                return;
            }
        } elseif (!is_string($value)) {
            $this->addError('The given value was not a valid string.', 1269883975);

            return;
        }

        $stringLength = mb_strlen((string)$value, 'utf-8');

        if ($stringLength < $this->options['minimum'] || $stringLength > $this->options['maximum']) {
            $this->addError('stringLength', 1428504122, [$this->options['minimum'], $this->options['maximum']]);
        }
    }
}