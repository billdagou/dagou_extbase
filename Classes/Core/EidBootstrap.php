<?php
namespace Dagou\DagouExtbase\Core;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Core\Bootstrap;
use TYPO3\CMS\Extbase\Core\BootstrapInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;
use TYPO3\CMS\Frontend\Utility\EidUtility;

class EidBootstrap implements BootstrapInterface {
    /**
     * @var array
     */
    protected $configuration;

    public function __construct() {
        $this->initializeTSFE()->initializeConfiguration();
    }

    /**
     * @return \Dagou\DagouExtbase\Core\EidBootstrap
     */
    protected function initializeConfiguration() {
        $this->configuration = GeneralUtility::_GET();
        unset($this->configuration['eID']);

        ArrayUtility::mergeRecursiveWithOverrule($this->configuration, GeneralUtility::_POST());

        return $this;
    }

    /**
     * @return \Dagou\DagouExtbase\Core\EidBootstrap
     */
    protected function initializeTSFE() {
        $GLOBALS['TSFE'] = GeneralUtility::makeInstance(
            TypoScriptFrontendController::class,
            NULL,
            GeneralUtility::_GP('id'),
            GeneralUtility::_GP('type'),
            GeneralUtility::_GP('no_cache'),
            GeneralUtility::_GP('cHash'),
            NULL,
            GeneralUtility::_GP('MP'),
            GeneralUtility::_GP('RDCT')
        );

        $GLOBALS['TSFE']->connectToDB();
        EidUtility::initTCA();
        $GLOBALS['TSFE']->initFEuser();
        $GLOBALS['TSFE']->checkAlternativeIdMethods();
        $GLOBALS['TSFE']->clear_preview();
        $GLOBALS['TSFE']->determineId();
        $GLOBALS['TSFE']->initTemplate();
        $GLOBALS['TSFE']->getConfigArray();
        $GLOBALS['TSFE']->settingLanguage();
        $GLOBALS['TSFE']->settingLocale();

        $GLOBALS['TSFE']->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);

        return $this;
    }

    /**
     * @param string $content
     * @param array $configuration
     *
     * @return string
     */
    public function run($content, $configuration) {
        $bootstrap = GeneralUtility::makeInstance(Bootstrap::class);

        ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $configuration);

        return $bootstrap->run('', $this->configuration);
    }
}