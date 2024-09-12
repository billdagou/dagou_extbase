<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait ValidatorResolver {
    protected \TYPO3\CMS\Extbase\Validation\ValidatorResolver $validatorResolver;

    /**
     * @param \TYPO3\CMS\Extbase\Validation\ValidatorResolver $validatorResolver
     *
     * @return void
     */
    public function injectValidatorResolver(\TYPO3\CMS\Extbase\Validation\ValidatorResolver $validatorResolver): void {
        $this->validatorResolver = $validatorResolver;
    }
}