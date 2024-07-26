<?php
namespace Dagou\DagouExtbase\Traits\Inject;

trait PropertyMapper {
    protected \TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper;

    /**
     * @param \TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper
     */
    public function injectPropertyMapper(\TYPO3\CMS\Extbase\Property\PropertyMapper $propertyMapper): void {
        $this->propertyMapper = $propertyMapper;
    }
}