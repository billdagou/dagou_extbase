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
        'value' => ['', 'The value to compare with', 'mixed', TRUE],
        'op' => ['', 'Greater than or equal', 'string', TRUE],
        'property' => ['', 'Property path', 'string'],
    ];

    /**
     * @param mixed $value
     *
     * @throws \TYPO3\CMS\Extbase\Validation\Exception\InvalidValidationOptionsException
     */
    public function isValid(mixed $value): void {
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
    protected function compare(mixed $value1, mixed $value2, string $op): bool {
        return match ($op) {
            'eq' => $value1 == $value2,
            'seq' => $value1 === $value2,
            'gt' => $value1 > $value2,
            'gte' => $value1 >= $value2,
            'lt' => $value1 < $value2,
            'lte' => $value1 <= $value2,
            'neq' => $value1 != $value2,
            'nseq' => $value1 !== $value2,
            default => FALSE,
        };
    }
}