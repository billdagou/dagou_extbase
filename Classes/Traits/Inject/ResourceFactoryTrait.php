<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Core\Resource\ResourceFactory;

trait ResourceFactoryTrait {
    protected ResourceFactory $resourceFactory;

    /**
     * @param \TYPO3\CMS\Core\Resource\ResourceFactory $resourceFactory
     */
    public function injectResourceFactory(ResourceFactory $resourceFactory): void {
        $this->resourceFactory = $resourceFactory;
    }
}