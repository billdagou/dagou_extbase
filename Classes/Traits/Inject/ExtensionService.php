<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait ExtensionService {
    /**
     * @var \TYPO3\CMS\Extbase\Service\ExtensionService
     */
    protected $extensionService;

    /**
     * @param \TYPO3\CMS\Extbase\Service\ExtensionService $extensionService
     */
    public function injectExtensionService(\TYPO3\CMS\Extbase\Service\ExtensionService $extensionService) {
        $this->extensionService = $extensionService;
    }
}