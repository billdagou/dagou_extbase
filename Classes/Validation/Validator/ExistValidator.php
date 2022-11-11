<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class ExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function isValid($value) {
        if ($this->count($value) === 0) {
            $this->addError('exist', 1459494450);
        }
    }
}