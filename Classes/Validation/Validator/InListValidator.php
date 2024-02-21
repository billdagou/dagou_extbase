<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class InListValidator extends AbstractValidator{
    /**
     * @var array
     */
    protected $supportedOptions = [
        'list' => [[], 'The value list', 'mixed'],
        'strict' => [FALSE, 'Is strict or not', 'boolean'],
    ];

    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void {
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
    protected function isInList(mixed $value): bool {
        if ($this->options['list'] instanceof ObjectStorage) {
            return $this->options['list']->contains($value);
        } elseif ($this->options['list'] instanceof QueryResultInterface) {
            /** @var \TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface $domainObject */
            foreach ($this->options['list'] as $domainObject) {
                if ($value === $domainObject) {
                    return TRUE;
                }
            }

            return FALSE;
        } else {
            if ($value instanceof DomainObjectInterface) {
                foreach ($this->options['list'] as $item) {
                    if ($value->getUid() === $item->getUid()) {
                        return TRUE;
                    }
                }

                return FALSE;
            }

            return in_array($value, $this->options['list'], $this->options['strict']);
        }
    }
}