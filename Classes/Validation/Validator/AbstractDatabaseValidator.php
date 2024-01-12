<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

abstract class AbstractDatabaseValidator extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'table' => ['', 'Table name', 'string', TRUE],
        'field' => ['', 'Field name', 'string', TRUE],
        'exclude' => [[], 'Excluded criteria', 'array'],
        'enableDeleted' => [TRUE, 'Enable deleted field', 'boolean'],
        'enableHidden' => [TRUE, 'Enable hidden field', 'boolean'],
    ];

    /**
     * @param mixed $value
     *
     * @return int
     * @throws \Doctrine\DBAL\Exception
     */
    protected function count(mixed $value): int {
        return (int)$this->getQueryBuilder($value)->executeQuery()->fetchOne();
    }

    /**
     * @param mixed $value
     *
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilder(mixed $value): QueryBuilder {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->options['table'])
            ->count($this->options['field'])
            ->from($this->options['table']);

        $queryBuilder->where($queryBuilder->expr()->eq($this->options['field'], $queryBuilder->createNamedParameter($value)));

        foreach ($this->options['exclude'] as $field => $value) {
            $queryBuilder->andWhere($queryBuilder->expr()->neq($field, $queryBuilder->createNamedParameter($value)));
        }

        if (!$this->options['enableDeleted']) {
            $queryBuilder->getRestrictions()->removeByType(DeletedRestriction::class);
        }

        if (!$this->options['enableHidden']) {
            $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        }

        return $queryBuilder;
    }
}