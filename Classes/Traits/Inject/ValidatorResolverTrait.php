<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Validation\ValidatorResolver;

trait ValidatorResolverTrait {
    protected ValidatorResolver $validatorResolver;

    /**
     * @param \TYPO3\CMS\Extbase\Validation\ValidatorResolver $validatorResolver
     *
     * @return void
     */
    public function injectValidatorResolver(ValidatorResolver $validatorResolver): void {
        $this->validatorResolver = $validatorResolver;
    }
}