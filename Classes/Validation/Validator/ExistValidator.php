<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class ExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     *
     * @throws \Doctrine\DBAL\Exception
     */
    protected function isValid(mixed $value): void {
        if ($this->count($value) === 0) {
            $this->addError('exist', 1459494450);
        }
    }
}