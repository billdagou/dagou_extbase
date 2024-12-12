<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Reflection\ReflectionService;

trait ReflectionServiceTrait {
    protected ReflectionService $reflectionService;

    /**
     * @param \TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService
     *
     * @return void
     */
    public function injectReflectionService(ReflectionService $reflectionService): void {
        $this->reflectionService = $reflectionService;
    }
}