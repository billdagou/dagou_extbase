<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class NotExistValidator extends AbstractDatabaseValidator
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

        if ($connection->count($columnName, $tableName, $where)) {
            $this->addError(
                $this->translateErrorMessage('validator.not_exist', 'dagou_extbase', [
                    $columnName,
                    $tableName,
                ]),
                1505139375
            );
        }
    }
}