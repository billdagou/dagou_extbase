<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Property\PropertyMapper;

trait PropertyMapperTrait {
    protected PropertyMapper $propertyMapper;

    /**
     * @param \TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper
     */
    public function injectPropertyMapper(PropertyMapper $propertyMapper): void {
        $this->propertyMapper = $propertyMapper;
    }
}