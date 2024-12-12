<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;

trait UriBuilderTrait {
    protected UriBuilder $uriBuilder;

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder
     */
    public function injectUriBuilder(UriBuilder $uriBuilder): void {
        $this->uriBuilder = $uriBuilder;
    }
}