<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class EmailAddressValidator extends \TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'emailAddress';
    }
}