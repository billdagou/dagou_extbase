<?php
namespace Dagou\DagouExtbase\Traits\Inject;

use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

trait SettingsTrait {
    protected ConfigurationManagerInterface $configurationManager;
    protected array $settings = [];

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void {
        $this->configurationManager = $configurationManager;

        $this->settings = $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,
            explode('\\', static::class)[1]
        );
    }
}