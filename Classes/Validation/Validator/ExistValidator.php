<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class ExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     */
    protected function isValid($value) {
        $this->initialize();

        $where = $this->getWhereClause($value);

        if (!$this->getConnection()->count($this->columnName, $this->tableName, $where)) {
            $this->addError(
                $this->translateErrorMessage(
                    'validator.exist',
                    'dagou_extbase',
                    [
                        $this->columnName,
                        $this->tableName,
                    ]
                ),
                1459494450
            );
        }
    }
}