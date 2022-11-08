<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait ResourceFactory {
    protected \TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory;

    /**
     * @param \TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory
     */
    public function injectResourceFactory(\TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory) {
        $this->resourceFactory = $resourceFactory;
    }
}