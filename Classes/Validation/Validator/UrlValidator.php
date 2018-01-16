<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class UrlValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * @param mixed $value
     */
    protected function isValid($value)
    {
        if (!GeneralUtility::isValidUrl($value)) {
            $this->addError($this->translateErrorMessage('validator.url', 'dagou_extbase'), 1458899916);
        }
    }
}