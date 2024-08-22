<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractGenericObjectValidator;

class GenericArrayValidator extends AbstractGenericObjectValidator {
    /**
     * @param mixed $value
     *
     * @return \TYPO3\CMS\Extbase\Error\Result
     */
    public function validate(mixed $value): Result {
        if (!is_array($value)) {
            $this->result = new Result();

            $this->addError('Array expected, %1$s given.', 1577978601, [gettype($value)]);

            return $this->result;
        } else {
            return parent::validate(
                json_decode(
                    json_encode($value)
                )
            );
        }
    }
}