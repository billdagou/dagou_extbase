<?php
namespace Dagou\DagouExtbase\Persistence;

use Dagou\DagouExtbase\DomainObject\ConditionInterface;

class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    /**
     * @param \Dagou\DagouExtbase\DomainObject\ConditionInterface|NULL $condition
     *
     * @return int
     */
    public function count(ConditionInterface $condition = NULL) {
        $query = $this->createConditionQuery($condition);

        return $query->execute()->count();
    }

    /**
     * @param \Dagou\DagouExtbase\DomainObject\ConditionInterface|NULL $condition
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected function createConditionQuery($condition = NULL) {
        return $this->createQuery();
    }

    /**
     * @param \Dagou\DagouExtbase\DomainObject\ConditionInterface|NULL $condition
     *
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function find(ConditionInterface $condition = NULL) {
        $query = $this->createConditionQuery($condition);

        if (($orderings = $condition->getOrderings())) {
            $query->setOrderings($orderings);
        }

        if (($limit = $condition->getLimit()) && $limit > 0) {
            $query->setLimit($limit);

            if (($page = $condition->getPage()) && $page > 1) {
                $query->setOffset(($page - 1) * $limit);
            }
        }

        return $query->execute();
    }

    public function persistAll() {
        $this->persistenceManager->persistAll();
    }
}