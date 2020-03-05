<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class InListValidator extends AbstractValidator{
    /**
     * @var array
     */
    protected $supportedOptions = [
        'list' => [[], 'The value list', 'mixed'],
    ];

    /**
     * @param mixed $value
     */
    public function isValid($value) {
        if (is_array($value) || $value instanceof \Iterator) {
            foreach ($value as $v) {
                if (!$this->isInList($v)) {
                    $this->addError('inList', 1562659501);

                    break;
                }
            }
        } else {
            if (!$this->isInList($value)) {
                $this->addError('inList', 1562659501);
            }
        }
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected function isInList($value): bool {
        if ($this->options['list'] instanceof ObjectStorage) {
            return $this->options['list']->contains($value);
        } else {
            return in_array($value, $this->options['list']);
        }
    }
}