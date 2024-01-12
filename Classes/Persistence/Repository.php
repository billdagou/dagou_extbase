<?php
namespace Dagou\DagouExtbase\Persistence;

use Dagou\DagouExtbase\DomainObject\AbstractCriteria;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class Repository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    /**
     * @param object $object
     *
     * @return $this
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function add($object): self {
        parent::add($object);

        return $this;
    }

    /**
     * @param object $object
     *
     * @return $this
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function remove($object): self {
        parent::remove($object);

        return $this;
    }

    /**
     * @param object $modifiedObject
     *
     * @return $this
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     */
    public function update($modifiedObject): self {
        parent::update($modifiedObject);

        return $this;
    }

    /**
     * @return $this
     */
    public function persist(): self {
        $this->persistenceManager->persistAll();

        return $this;
    }

    /**
     * @param mixed $criteria
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function find(mixed $criteria = NULL): QueryResultInterface {
        $query = $this->createCriteriaQuery($criteria);

        return $query->execute();
    }

    /**
     * @param mixed $criteria
     *
     * @return \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function findOne(mixed $criteria = NULL): DomainObjectInterface {
        $query = $this->createCriteriaQuery($criteria);

        return $query->execute()->getFirst();
    }

    /**
     * @param mixed $criteria
     *
     * @return int
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    public function count(mixed $criteria = NULL): int {
        $query = $this->createCriteriaQuery($criteria);

        return $query->execute()->count();
    }

    /**
     * @param mixed $criteria
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    protected function createCriteriaQuery(mixed $criteria = NULL): QueryInterface {
        if ($criteria !== NULL && !is_array($criteria) && !$criteria instanceof AbstractCriteria) {
            throw new IllegalObjectTypeException('The criteria given to createCriteriaQuery() must be NULL, array or of ' . AbstractCriteria::class . '.', 1588312025);
        }

        return $this->createQuery();
    }
}