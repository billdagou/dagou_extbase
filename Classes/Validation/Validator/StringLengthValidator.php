<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class StringLengthValidator extends \TYPO3\CMS\Extbase\Validation\Validator\StringLengthValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'stringLength';
    }
}