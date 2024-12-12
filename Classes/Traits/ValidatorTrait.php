<?php
namespace Dagou\DagouExtbase\Traits;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

trait ValidatorTrait {
    /**
     * @param string $validatorClassName
     * @param array $options
     *
     * @return \TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface
     */
    protected function getValidator(string $validatorClassName, array $options = []): ValidatorInterface {
        /** @var \TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface $validator */
        $validator = GeneralUtility::makeInstance($validatorClassName);

        if (count($options) > 0) {
            $validator->setOptions($options);
        }

        return $validator;
    }
}