<?php
namespace Dagou\DagouExtbase\Persistence;

class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @param \Dagou\DagouExtbase\DomainObject\ConditionInterface|NULL $condition
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    protected function conditionQuery($condition = NULL)
    {
        return $this->createQuery();
    }

    /**
     * @param \Dagou\DagouExtbase\DomainObject\ConditionInterface|NULL $condition
     * @return int
     */
    public function count(\Dagou\DagouExtbase\DomainObject\ConditionInterface $condition = NULL)
    {
        $query = $this->conditionQuery($condition);

        return $query->execute()->count();
    }

    /**
     * @param \Dagou\DagouExtbase\DomainObject\ConditionInterface|NULL $condition
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function find(\Dagou\DagouExtbase\DomainObject\ConditionInterface $condition = NULL)
    {
        $query = $this->conditionQuery($condition);

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

    public function persistAll()
    {
        $this->persistenceManager->persistAll();
    }
}