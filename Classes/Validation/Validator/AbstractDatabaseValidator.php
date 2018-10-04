<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use Dagou\DagouExtbase\Traits\Database;
use Dagou\DagouExtbase\Traits\DataMapper;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

abstract class AbstractDatabaseValidator extends AbstractValidator {
    use Database, DataMapper;
    /**
     * @var array
     */
    protected $supportedOptions = [
        'className' => ['', 'Class name', 'string', TRUE],
        'property' => ['', 'Property', 'string', TRUE],
        'deleted' => ['', 'Deleted field', 'string'],
        'hidden' => ['', 'Deleted field', 'string'],
    ];

    protected function initialize() {
        $this->tableName = $this->dataMapper->convertClassNameToTableName($this->options['className']);
        $this->columnName =
            $this->dataMapper->convertPropertyNameToColumnName($this->options['property'], $this->options['className']);
    }

    /**
     * @param mixed $value
     *
     * @return array
     */
    protected function getWhereClause($value) {
        $where = [
            $this->columnName => $value,
        ];

        if ($this->options['deleted']) {
            $where[$this->options['deleted']] = FALSE;
        }
        if ($this->options['hidden']) {
            $where[$this->options['hidden']] = FALSE;
        }

        return $where;
    }
}