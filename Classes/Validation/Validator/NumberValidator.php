<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class NumberValidator extends \TYPO3\CMS\Extbase\Validation\Validator\NumberValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'number';
    }
}