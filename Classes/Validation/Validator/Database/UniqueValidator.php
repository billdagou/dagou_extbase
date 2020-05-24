<?php
namespace Dagou\DagouExtbase\Validation\Validator\Database;

use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class UniqueValidator extends AbstractDatabaseValidator {
    /**
     * @param array $options
     */
    public function __construct(array $options = []) {
        $this->supportedOptions['exclude'] = [[], 'Excluded criterias', 'array'];

        parent::__construct($options);
    }

    /**
     * @param mixed $value
     */
    protected function isValid($value) {
        if ($this->count($value) > 0) {
            $this->addError('database.unique', 1590306297);
        }
    }

    /**
     * @param $value
     *
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuider($value):QueryBuilder {
        $queryBuilder = parent::getQueryBuider($value);

        foreach ($this->options['exclude'] as $field => $value) {
            $queryBuilder->andWhere($queryBuilder->expr()->neq($field, $queryBuilder->createNamedParameter($value)));
        }

        return $queryBuilder;
    }
}