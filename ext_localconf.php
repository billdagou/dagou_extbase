<?php
defined('TYPO3') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(\Dagou\DagouExtbase\Property\TypeConverter\UploadedFileReferenceConverter::class);