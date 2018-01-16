<?php
namespace Dagou\DagouExtbase\DomainObject;

abstract class AbstractCondition implements ConditionInterface
{
    /**
     * @var array
     */
    protected $orderings;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $page;

    /**
     * @param array $orderings
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setOrderings(array $orderings = [])
    {
        $this->orderings = $orderings;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderings()
    {
        return $this->orderings;
    }

    /**
     * @param int $limit
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setLimit(int $limit = 0)
    {
        $this->limit = $limit >= 0 ? $limit : 0;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $page
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setPage(int $page = 1)
    {
        $this->page = $page > 0 ? $page : 1;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
}