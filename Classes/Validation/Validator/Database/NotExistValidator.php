<?php
namespace Dagou\DagouExtbase\Validation\Validator\Database;

class NotExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     */
    protected function isValid($value) {
        if ($this->count($value) > 0) {
            $this->addError('database.notExist', 1505139375);
        }
    }
}