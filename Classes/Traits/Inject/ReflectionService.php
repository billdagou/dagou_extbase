<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait ReflectionService {
    protected \TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService;

    /**
     * @param \TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService
     *
     * @return void
     */
    public function injectReflectionService(\TYPO3\CMS\Extbase\Reflection\ReflectionService $reflectionService): void {
        $this->reflectionService = $reflectionService;
    }
}