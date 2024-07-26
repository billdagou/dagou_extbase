<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

trait PersistenceManager {
    protected PersistenceManagerInterface $persistenceManager;

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager): void {
        $this->persistenceManager = $persistenceManager;
    }
}