<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

trait PersistenceManager {
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface $persistenceManager
     */
    public function injectPersistenceManager(PersistenceManagerInterface $persistenceManager) {
        $this->persistenceManager = $persistenceManager;
    }
}