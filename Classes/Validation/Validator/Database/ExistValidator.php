<?php
namespace Dagou\DagouExtbase\Validation\Validator\Database;

class ExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     */
    protected function isValid($value) {
        if ($this->count($value) === 0) {
            $this->addError('database.exist', 1459494450);
        }
    }
}