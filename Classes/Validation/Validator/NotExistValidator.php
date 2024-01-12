<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class NotExistValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     *
     * @throws \Doctrine\DBAL\Exception
     */
    protected function isValid(mixed $value): void {
        if ($this->count($value) > 0) {
            $this->addError('notExist', 1505139375);
        }
    }
}