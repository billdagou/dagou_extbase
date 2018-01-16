<?php
namespace Dagou\DagouExtbase\Validation\Validator;

abstract class AbstractDatabaseValidator extends \TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator
{
    /**
     * @var \TYPO3\CMS\Core\Database\ConnectionPool
     */
    protected $connectionPool;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
     */
    protected $dataMapper;

    /**
     * @var array
     */
    protected $supportedOptions = [
        'className' => ['', 'Class name', 'string', TRUE],
        'property' => ['', 'Property', 'string', TRUE],
        'deleted' => ['', 'Deleted field', 'string'],
        'hidden' => ['', 'Deleted field', 'string'],
    ];

    /**
     * @param \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool
     */
    public function injectConnectionPool(\TYPO3\CMS\Core\Database\ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
     */
    public function injectDataMapper(\TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }
}