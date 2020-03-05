<?php
namespace Dagou\DagouExtbase\Persistence;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    /**
     * @param object $object
     *
     * @return \Dagou\DagouExtbase\Persistence\Repository
     */
    public function add($object): Repository {
        parent::add($object);

        return $this;
    }

    /**
     * @param object $object
     *
     * @return \Dagou\DagouExtbase\Persistence\Repository
     */
    public function remove($object): Repository {
        parent::remove($object);

        return $this;
    }

    /**
     * @param object $modifiedObject
     *
     * @return \Dagou\DagouExtbase\Persistence\Repository
     */
    public function update($modifiedObject): Repository {
        parent::update($modifiedObject);

        return $this;
    }

    /**
     * @return \Dagou\DagouExtbase\Persistence\Repository
     */
    public function persist(): Repository {
        $this->persistenceManager->persistAll();

        return $this;
    }

    /**
     * @param array $condition
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findByCondition(array $condition): QueryResultInterface {
        $query = $this->createConditionQuery($condition);

        return $query->execute();
    }

    /**
     * @param array $condition
     *
     * @return int
     */
    public function countByCondition(array $condition): int {
        $query = $this->createConditionQuery($condition);

        return $query->execute()->count();
    }

    /**
     * @param array $condition
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected function createConditionQuery(array $condition): QueryInterface {
        return $this->createQuery();
    }
}