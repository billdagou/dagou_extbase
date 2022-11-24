<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(\Dagou\DagouExtbase\Property\TypeConverter\UploadedFileReferenceConverter::class);