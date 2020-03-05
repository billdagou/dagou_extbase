<?php
namespace Dagou\DagouExtbase\Validation\Validator\Database;

use TYPO3\CMS\Core\Database\ConnectionPool;
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
        'enableDeleted' => [TRUE, 'Enable deleted field', 'boolean'],
        'enableHidden' => [TRUE, 'Enable hidden field', 'boolean'],
    ];

    /**
     * @param mixed $value
     *
     * @return int
     */
    protected function count($value): int {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->options['table'])
            ->count($this->options['field'])
            ->from($this->options['table']);

        $queryBuilder->where($queryBuilder->expr()->eq($this->options['field'], $queryBuilder->createNamedParameter($value)));

        if (!$this->options['enableDeleted']) {
            $queryBuilder->getRestrictions()->removeByType(DeletedRestriction::class);
        }

        if (!$this->options['enableHidden']) {
            $queryBuilder->getRestrictions()->removeByType(HiddenRestriction::class);
        }

        return (int)$queryBuilder->execute()->fetchColumn(0);
    }
}