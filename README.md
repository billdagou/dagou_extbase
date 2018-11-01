# TYPO3 Extension: Dagou Extbase
TYPO3 Extbase Framework - Dagou edition

## Domain Object
#### AbstractCondition
Build your own condition class to retrieve the model objects flexibly with the help of th new repository.

## HTTP
#### EidRequestHandler
Overwriting the orginal EidRequestHandler to code eID in extbase easily.

## MVC
#### ActionController
Hide the error flash message.

## Persistence
#### Repository
Extend its possibility to retrieve different model objects in one function.

## Service
#### RteService
Helper service to convert from RTE to DB and backward.

## Utility
#### LocalizationUtility
Localization helper which could fetch localized labels via specific language key.

## Validation
#### ExistValidator
Validate if the value is existed in the database.

    @validate $domainObject.field Dagou.DagouExtbase:Exist(className = Vendor\ExtensionName\Domain\Model\DomainObject, property = field)
    
- `className` Domain object class name. **Required**.
- `property` Property name. **Required**.
- `deleted` Deleted field name.
- `hidden` Hidden field name.

#### NotExistValidator
Validate if the value is not existed in the database.

    @validate $domainObject.field Dagou.DagouExtbase:NotExist(className = Vendor\ExtensionName\Domain\Model\DomainObject, property = field)
    
- `className` Domain object class name. **Required**.
- `property` Property name. **Required**.
- `deleted` Deleted field name.
- `hidden` Hidden field name.

#### UniqueValidator
Validate if the value is unique in the database.

    @validate $domainObject.field Dagou.DagouExtbase:Unique(className = Vendor\ExtensionName\Domain\Model\DomainObject, property = field)
    
- `className` Domain object class name. **Required**.
- `property` Property name. **Required**.
- `deleted` Deleted field name.
- `hidden` Hidden field name.

#### UrlValidator
Validate if it's a valid URL.

    @validate $value Dagou.DagouExtbase:Url