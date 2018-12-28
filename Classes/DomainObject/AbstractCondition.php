<?php
namespace Dagou\DagouExtbase\DomainObject;

use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

abstract class AbstractCondition extends AbstractDomainObject implements ConditionInterface {
    /**
     * @var int
     */
    protected $limit;
    /**
     * @var array
     */
    protected $orderings;
    /**
     * @var int
     */
    protected $page;

    /**
     * @return int
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setLimit(int $limit = 0) {
        $this->limit = $limit > 0 ? $limit : 0;

        return $this;
    }

    /**
     * @return array
     */
    public function getOrderings() {
        return $this->orderings;
    }

    /**
     * @param array $orderings
     *
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setOrderings(array $orderings = []) {
        $this->orderings = $orderings;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return \Dagou\DagouExtbase\DomainObject\ConditionInterface
     */
    public function setPage(int $page = 1) {
        $this->page = $page > 1 ? $page : 1;

        return $this;
    }
}