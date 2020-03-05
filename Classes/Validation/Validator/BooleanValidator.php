<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class BooleanValidator extends \TYPO3\CMS\Extbase\Validation\Validator\BooleanValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'boolean';
    }
}