<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Validation\Exception\InvalidValidationOptionsException;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class CompareValidator extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'value' => ['', 'The timestamp to compare', 'mixed', TRUE],
        'op' => ['', 'Greater than or equal', 'string', TRUE],
        'property' => ['', 'Property path', 'string'],
    ];

    public function isValid($value) {
        if ($this->options['property']) {
            foreach (explode('.', $this->options['property']) as $property) {
                if ($value instanceof DomainObjectInterface && $value->_hasProperty($property)) {
                    $value = $value->_getProperty($property);
                } elseif (is_array($value) && isset($value[$property])) {
                    $value = $value[$property];
                } else {
                    throw new InvalidValidationOptionsException('No such property('.$property.') in '.(is_object($value) ? get_class($value) : gettype($value)), 1579098734);
                }
            }
        }

        if (!$this->compare($value, $this->options['value'], $this->options['op'])) {
            $this->addError(($this->options['property'] ? $this->options['property'].'.' : '').'compare.'.$this->options['op'], 1571841830);
        }
    }

    /**
     * @param mixed $value1
     * @param mixed $value2
     * @param string $op
     *
     * @return bool
     */
    protected function compare($value1, $value2, string $op): bool {
        switch ($op) {
            case 'eq':
                return $value1 == $value2;
            case 'seq':
                return $value1 === $value2;
            case 'gt':
                return $value1 > $value2;
            case 'gte':
                return $value1 >= $value2;
            case 'lt':
                return $value1 < $value2;
            case 'lte':
                return $value1 <= $value2;
            case 'neq':
                return $value1 != $value2;
            case 'nseq':
                return $value1 !== $value2;
        }

        return FALSE;
    }
}