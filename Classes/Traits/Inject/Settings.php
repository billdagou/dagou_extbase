<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class Settings {
    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;
    /**
     * @var array
     */
    protected $settings;

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
        $this->configurationManager = $configurationManager;
        $this->settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            explode('\\', static::class)[1]
        );
    }
}