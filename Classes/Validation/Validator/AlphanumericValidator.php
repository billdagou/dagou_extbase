<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class AlphanumericValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AlphanumericValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'alphanumeric';
    }
}