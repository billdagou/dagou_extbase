<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Service\ExtensionService;

trait ExtensionServiceTrait {
    protected ExtensionService $extensionService;

    /**
     * @param \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService
     */
    public function injectExtensionService(ExtensionService $extensionService): void {
        $this->extensionService = $extensionService;
    }
}