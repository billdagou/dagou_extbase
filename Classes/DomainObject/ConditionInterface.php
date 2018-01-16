<?php
namespace Dagou\DagouExtbase\DomainObject;

interface ConditionInterface
{
    /**
     * @param array $orderings
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setOrderings(array $orderings = []);

    /**
     * @return array
     */
    public function getOrderings();

    /**
     * @param int $limit
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setLimit(int $limit = 0);

    /**
     * @return int
     */
    public function getLimit();

    /**
     * @param int $page
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setPage(int $page = 1);

    /**
     * @return int
     */
    public function getPage();
}