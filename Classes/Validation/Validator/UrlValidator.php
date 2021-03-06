<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class UrlValidator extends \TYPO3\CMS\Extbase\Validation\Validator\UrlValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'url';
    }
}