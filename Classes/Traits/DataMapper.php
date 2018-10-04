<?php
namespace Dagou\DagouExtbase\Traits;

use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper as DM;

trait DataMapper {
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper
     */
    protected $dataMapper;

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper $dataMapper
     */
    public function injectDataMapper(DM $dataMapper) {
        $this->dataMapper = $dataMapper;
    }
}