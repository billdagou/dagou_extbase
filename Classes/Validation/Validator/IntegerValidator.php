<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class IntegerValidator extends \TYPO3\CMS\Extbase\Validation\Validator\IntegerValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'integer';
    }
}