<?php
namespace Dagou\DagouExtbase\Service;

use TYPO3\CMS\Core\Configuration\Richtext;
use TYPO3\CMS\Core\Html\RteHtmlParser;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class RteService implements SingletonInterface {
    protected RteHtmlParser $rteHtmlParser;
    protected ConfigurationManagerInterface $configurationManager;

    /**
     * @param \TYPO3\CMS\Core\Html\RteHtmlParser $rteHtmlParser
     */
    public function injectRteHtmlParser(RteHtmlParser $rteHtmlParser) {
        $this->rteHtmlParser = $rteHtmlParser;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager) {
        $this->configurationManager = $configurationManager;
    }

    /**
     * @param string $value
     * @param string $table
     * @param string $field
     * @param string|null $recordType
     *
     * @return string
     */
    public function transformDbToRte(string $value, string $table, string $field, ?string $recordType = NULL): string {
        return $this->rteHtmlParser->transformTextForRichTextEditor(
            $value,
            $this->getRteConfiguration($table, $field, $recordType)['proc.'] ?? []
        );
    }

    /**
     * @param string $value
     * @param string $table
     * @param string $field
     * @param string|null $recordType
     *
     * @return string
     */
    public function transformRteToDb(string $value, string $table, string $field, ?string $recordType = NULL): string {
        return $this->rteHtmlParser->transformTextForPersistence(
            $value,
            $this->getRTEConfiguration($table, $field, $recordType)['proc.'] ?? []
        );
    }

    /**
     * @param string $table
     * @param string $field
     * @param string|null $recordType
     *
     * @return array
     */
    protected function getRteConfiguration(string $table, string $field, ?string $recordType = NULL): array {
        return GeneralUtility::makeInstance(Richtext::class)
            ->getConfiguration(
                $table,
                $field,
                $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK)['persistence']['storagePid'],
                $recordType ?? $this->getTcaTypeValue($table),
                $GLOBALS['TCA'][$table]['columns'][$field]['config']
            );
    }

    /**
     * @param string $table
     *
     * @return string
     */
    protected function getTcaTypeValue(string $table): string {
        $typeNum = 0;

        if (!isset($GLOBALS['TCA'][$table]['types'][$typeNum]) || !$GLOBALS['TCA'][$table]['types'][$typeNum]) {
            $typeNum = isset($GLOBALS['TCA'][$table]['types']['0']) ? 0 : 1;
        }

        return $typeNum;
    }
}