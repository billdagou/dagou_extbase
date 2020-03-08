<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class StringValidator extends \TYPO3\CMS\Extbase\Validation\Validator\StringValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'string';
    }
}