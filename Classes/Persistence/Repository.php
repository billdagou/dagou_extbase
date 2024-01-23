<?php
namespace Dagou\DagouExtbase\Persistence;

use Dagou\DagouExtbase\Persistence\Generic\Storage\Typo3DbQueryParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
     * @param string $fieldName
     * @param array $criteria
     *
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    public function sumBy(string $fieldName, array $criteria): int {
        $query = $this->createQuery();
        $constraints = [];
        foreach ($criteria as $propertyName => $propertyValue) {
            $constraints[] = $query->equals($propertyName, $propertyValue);
        }

        if (($numberOfConstraints = count($constraints)) === 1) {
            $query->matching(...$constraints);
        } elseif ($numberOfConstraints > 1) {
            $query->matching($query->logicalAnd(...$constraints));
        }

        $queryBuilder = GeneralUtility::makeInstance(Typo3DbQueryParser::class)
            ->convertQueryToDoctrineQueryBuilder($query);

        $result = $queryBuilder->addSelectLiteral(
                $queryBuilder->expr()->sum($fieldName, 'sum')
            )
            ->executeQuery();

        return $result->fetchAssociative()['sum'] ?? 0;
    }
}