<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait PropertyMapper {
    /**
     * @var \TYPO3\CMS\Extbase\Property\PropertyMapper
     */
    protected $propertyMapper;

    /**
     * @param \TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper
     */
    public function injectPropertyMapper(\TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper) {
        $this->propertyMapper = $propertyMapper;
    }
}