<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Exception\InvalidValidationOptionsException;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class RegularExpressionValidator extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'regularExpression' => ['', 'The regular expression to use for validation, used as given', 'string', TRUE],
    ];

    /**
     * @param mixed $value
     *
     * @throws \TYPO3\CMS\Extbase\Validation\Exception\InvalidValidationOptionsException
     */
    public function isValid(mixed $value): void {
        $result = preg_match($this->options['regularExpression'], $value);

        if ($result === 0) {
            $this->addError('regularExpression', 1221565130);
        }

        if ($result === FALSE) {
            throw new InvalidValidationOptionsException('regularExpression "' . $this->options['regularExpression'] . '" in RegularExpressionValidator contained an error.', 1298273089);
        }
    }
}