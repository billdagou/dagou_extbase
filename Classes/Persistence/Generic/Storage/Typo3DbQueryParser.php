<?php
namespace Dagou\DagouExtbase\Persistence\Generic\Storage;

use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\JoinInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\SelectorInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface;

#[Autoconfigure(public: TRUE, shared: FALSE)]
class Typo3DbQueryParser extends \TYPO3\CMS\Extbase\Persistence\Generic\Storage\Typo3DbQueryParser {
    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Qom\SourceInterface $source
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Generic\Exception
     */
    protected function initializeQueryBuilder(SourceInterface $source): void {
        if ($source instanceof SelectorInterface) {
            $className = $source->getNodeTypeName();
            $tableName = $this->dataMapper->getDataMap($className)->getTableName();
            $this->tableName = $tableName;

            $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($tableName);

            $this->queryBuilder
                ->getRestrictions()
                ->removeAll();

            $tableAlias = $this->getUniqueAlias($tableName);

            $this->queryBuilder
                ->from($tableName, $tableAlias);

            $this->addRecordTypeConstraint($className);
        } elseif ($source instanceof JoinInterface) {
            $leftSource = $source->getLeft();
            $leftTableName = $leftSource->getSelectorName();

            $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                ->getQueryBuilderForTable($leftTableName);
            $leftTableAlias = $this->getUniqueAlias($leftTableName);
            $this->queryBuilder
                ->from($leftTableName, $leftTableAlias);
            $this->parseJoin($source, $leftTableAlias);
        }
    }

    /**
     * @param string $className
     */
    protected function addRecordTypeConstraint($className) {}
}