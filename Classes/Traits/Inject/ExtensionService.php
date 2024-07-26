<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait ExtensionService {
    protected \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService;

    /**
     * @param \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService
     */
    public function injectExtensionService(\TYPO3\CMS\Extbase\Service\ExtensionService $extensionService): void {
        $this->extensionService = $extensionService;
    }
}