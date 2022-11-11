<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class NotExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function isValid($value) {
        if ($this->count($value) > 0) {
            $this->addError('notExist', 1505139375);
        }
    }
}