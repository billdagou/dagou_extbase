<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class UniqueValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    protected function isValid($value) {
        if ($this->count($value) !== 1) {
            $this->addError('unique', 1590306297);
        }
    }
}