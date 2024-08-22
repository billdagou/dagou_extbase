<?php
namespace Dagou\DagouExtbase\Validation\Validator;

use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyObjectStorage;
use TYPO3\CMS\Extbase\Utility\TypeHandlingUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractGenericObjectValidator;
use TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface;

class CollectionValidator extends AbstractGenericObjectValidator {
    /**
     * @var array
     */
    protected $supportedOptions = [
        'default' => [NULL, 'Default validator', ValidatorInterface::class],
    ];

    /**
     * @param mixed $value
     *
     * @return \TYPO3\CMS\Extbase\Error\Result
     */
    public function validate(mixed $value): Result {
        $this->result = new Result();

        if ($this->acceptsEmptyValues === FALSE || $this->isEmpty($value) === FALSE) {
            if ((is_object($value) && !TypeHandlingUtility::isCollectionType(get_class($value))) && !is_array($value)) {
                $this->addError('The given subject was not a collection.', 1724311676);

                return $this->result;
            }
            if ($value instanceof LazyObjectStorage && !$value->isInitialized()) {
                return $this->result;
            }
            if (is_object($value)) {
                if ($this->isValidatedAlready($value)) {
                    return $this->result;
                }

                $this->markInstanceAsValidated($value);
            }

            $this->isValid($value);
        }

        return $this->result;
    }

    /**
     * @param mixed $object
     *
     * @return void
     */
    protected function isValid(mixed $object): void {
        $i = 0;

        foreach ($object as $collectionElement) {
            if (($validator = $this->propertyValidators[$i] ?? $this->options['default'] ?? NULL)) {
                $this->result->forProperty((string)$i)->merge($validator->validate($collectionElement));
            }

            $i++;
        }
    }
}