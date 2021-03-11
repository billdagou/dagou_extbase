<?php
namespace Dagou\DagouExtbase\Service;

use TYPO3\CMS\Core\Configuration\Richtext;
use TYPO3\CMS\Core\Html\RteHtmlParser;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RteService implements SingletonInterface {
    /**
     * @param string $value
     * @param string $table
     * @param string $field
     * @param string $recordType
     *
     * @return string
     */
    public function transformDbToRte(string $value, string $table, string $field, string $recordType = '0'): string {
        return $this->getRteHtmlParser()
            ->transformTextForPersistence(
                $value,
                $this->getRteConfiguration($table, $field, $recordType)
            );
    }

    /**
     * @param string $value
     * @param string $table
     * @param string $field
     * @param string $recordType
     *
     * @return string
     */
    public function transformRteToDb(string $value, string $table, string $field, string $recordType = '0'): string {
        return $this->getRteHtmlParser()
            ->transformTextForPersistence(
                $value,
                $this->getRTEConfiguration($table, $field, $recordType)
            );
    }

    /**
     * @return \TYPO3\CMS\Core\Html\RteHtmlParser
     */
    protected function getRteHtmlParser(): RteHtmlParser {
        return GeneralUtility::makeInstance(RteHtmlParser::class);
    }

    /**
     * @param string $table
     * @param string $field
     * @param string $recordType
     *
     * @return array
     */
    protected function getRteConfiguration(string $table, string $field, string $recordType): array {
        return GeneralUtility::makeInstance(Richtext::class)
            ->getConfiguration(
                $table,
                $field,
                $GLOBALS['TSFE']->id ?? 0,
                $recordType,
                [
                    'type' => 'text',
                    'enableRichtext' => TRUE,
                ]
            )['proc.'] ?? [];
    }
}