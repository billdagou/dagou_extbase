<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class BooleanValidator extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'is' => [NULL, 'Boolean value', 'boolean|string|integer'],
    ];

    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
        if ($this->options['is'] === NULL) {
            return;
        }

        switch (strtolower((string)$this->options['is'])) {
            case 'true':
            case '1':
                $expectation = TRUE;

                break;
            case 'false':
            case '':
            case '0':
                $expectation = FALSE;

                break;
            default:
                $this->addError('The given expectation is not valid.', 1361959227);

                return;
        }

        if ($value !== $expectation) {
            if (!is_bool($value)) {
                $this->addError('boolean', 1361959230);
            } else {
                if ($expectation) {
                    $this->addError('boolean', 1361959228);
                } else {
                    $this->addError('boolean', 1361959229);
                }
            }
        }
    }
}