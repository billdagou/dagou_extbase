<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class NumberRangeValidator extends \TYPO3\CMS\Extbase\Validation\Validator\NumberRangeValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'numberRange';
    }
}