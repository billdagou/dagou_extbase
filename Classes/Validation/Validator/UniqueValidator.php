<?php
namespace Dagou\DagouExtbase\Validation\Validator;

class UniqueValidator extends AbstractDatabaseValidator {
    /**
     * @param mixed $value
     *
     * @throws \Doctrine\DBAL\Exception
     */
    protected function isValid(mixed $value): void {
        if ($this->count($value) !== 0) {
            $this->addError('unique', 1590306297);
        }
    }
}