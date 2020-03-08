<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class FloatValidator extends \TYPO3\CMS\Extbase\Validation\Validator\FloatValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'float';
    }
}