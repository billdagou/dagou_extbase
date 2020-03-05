<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class CompareViewHelper extends AbstractValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'value' => ['', 'The timestamp to compare', 'mixed', TRUE],
        'op' => ['', 'Greater than or equal', 'string', TRUE],
    ];

    public function isValid($value) {
        if (!$this->compare($value, $this->options['value'], $this->options['op'])) {
            $this->addError('compare.'.$this->options['op']);
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
            case '==':
                return $value1 == $value2;
            case '===':
                return $value1 === $value2;
            case '>':
                return $value1 > $value2;
            case '>=':
                return $value1 >= $value2;
            case '<':
                return $value1 < $value2;
            case '<=':
                return $value1 <= $value2;
            case '<>':
                return $value1 <> $value2;
            case '!=':
                return $value1 != $value2;
            case '!==':
                return $value1 !== $value2;
        }

        return FALSE;
    }
}