<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait UriBuilder {
    /**
     * @var \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder
     */
    protected $uriBuilder;

    /**
     * @param \TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder
     */
    public function injectUriBuilder(\TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder $uriBuilder) {
        $this->uriBuilder = $uriBuilder;
    }
}