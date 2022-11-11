<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(\Dagou\DagouExtbase\Property\TypeConverter\UploadedFileReferenceConverter::class);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['be'] = [
    'TYPO3\\CMS\\Backend\\ViewHelpers',
];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['c'] = [
    'TYPO3\\CMS\\Core\\ViewHelpers',
];