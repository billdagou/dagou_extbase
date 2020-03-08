<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class RegularExpressionValidator extends \TYPO3\CMS\Extbase\Validation\Validator\RegularExpressionValidator {
    /**
     * @param string $translateKey
     * @param string $extensionName
     * @param array $arguments
     *
     * @return string
     */
    protected function translateErrorMessage($translateKey, $extensionName, $arguments = []): string {
        return 'regularExpression';
    }
}