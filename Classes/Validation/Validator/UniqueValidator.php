<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class UniqueValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     */
    protected function isValid($value) {
        $this->initialize();

        $where = $this->getWhereClause($value);

        if ($this->getConnection()->count($this->columnName, $this->tableName, $where) > 1) {
            $this->addError(
                $this->translateErrorMessage(
                    'validator.unique',
                    'dagou_extbase',
                    [
                        $this->columnName,
                        $this->tableName,
                    ]
                ),
                1459304412
            );
        }
    }
}