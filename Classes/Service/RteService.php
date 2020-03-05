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
     *
     * @return string
     */
    public function transformDbToRte(string $value, string $table, string $field): string {
        return $this->getRteHtmlParser($table, $field)->RTE_transform($value, NULL, 'rte', $this->getRteConfiguration($table, $field));
    }

    /**
     * @param string $value
     * @param string $table
     * @param string $field
     *
     * @return string
     */
    public function transformRteToDb(string $value, string $table, string $field): string {
        return $this->getRteHtmlParser($table, $field)->RTE_transform($value, NULL, 'db', $this->getRteConfiguration($table, $field));
    }

    /**
     * @param string $table
     * @param string $field
     *
     * @return \TYPO3\CMS\Core\Html\RteHtmlParser
     */
    protected function getRteHtmlParser(string $table, string $field): RteHtmlParser {
        $rteHtmlParser = GeneralUtility::makeInstance(RteHtmlParser::class);
        $rteHtmlParser->init($table.':'.$field, $GLOBALS['TSFE']->id);

        return $rteHtmlParser;
    }

    /**
     * @param string $table
     * @param string $field
     *
     * @return array
     */
    protected function getRteConfiguration(string $table, string $field): array {
        return GeneralUtility::makeInstance(Richtext::class)->getConfiguration($table, $field, $GLOBALS['TSFE']->id, 0, [
            'type' => 'text',
            'enableRichtext' => TRUE,
        ]);
    }
}