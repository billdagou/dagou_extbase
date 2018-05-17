<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Frontend\Http\EidRequestHandler::class] = [
    'className' => \Dagou\DagouExtbase\Http\EidRequestHandler::class,
];