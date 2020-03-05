<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class NotEmptyValidator extends \TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'notEmpty';
    }
}