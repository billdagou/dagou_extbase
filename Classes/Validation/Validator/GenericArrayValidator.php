<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Validation\Validator\GenericObjectValidator;

class GenericArrayValidator extends GenericObjectValidator {
    /**
     * @param mixed $value
     *
     * @return \TYPO3\CMS\Extbase\Error\Result
     */
    public function validate($value) {
        if (!is_array($value)) {
            $this->addError('Array expected, %1$s given.', 1577978601, [gettype($value)]);

            return new Result();
        } else {
            return parent::validate(
                json_decode(
                    json_encode($value)
                )
            );
        }
    }
}