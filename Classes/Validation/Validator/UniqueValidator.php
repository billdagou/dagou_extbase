<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class UniqueValidator extends AbstractDatabaseValidator
{
    /**
     * @param mixed $value
     */
    protected function isValid($value)
    {
        $tableName = $this->dataMapper->convertClassNameToTableName($this->options['className']);
        $columnName = $this->dataMapper->convertPropertyNameToColumnName($this->options['property'], $this->options['className']);

        $where = [
            $columnName => $value,
        ];
        if ($this->options['deleted']) {
            $where[$this->options['deleted']] = FALSE;
        }
        if ($this->options['hidden']) {
            $where[$this->options['hidden']] = FALSE;
        }

        $connection = $this->connectionPool->getConnectionForTable($tableName);

        if ($connection->count($columnName, $tableName, $where) > 1) {
            $this->addError(
                $this->translateErrorMessage('validator.unique', 'dagou_extbase', [
                    $columnName,
                    $tableName,
                ]),
                1459304412
            );
        }
    }
}