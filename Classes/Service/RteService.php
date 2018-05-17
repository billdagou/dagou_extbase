<?php
namespace Dagou\DagouExtbase\Service;

use TYPO3\CMS\Backend\Utility\BackendUtility;

class RteService implements \TYPO3\CMS\Core\SingletonInterface {
    /**
     * @var string
     */
    protected $field = '';
    /**
     * @var int
     */
    protected $pid = 0;
    /**
     * @var \TYPO3\CMS\Core\Html\RteHtmlParser
     */
    protected $rteHtmlParser;
    /**
     * @var string
     */
    protected $table = '';
    /**
     * @var array
     */
    protected $thisConfig = [];

    public function __construct() {
        if (TYPO3_MODE === 'BE') {
            if (TYPO3_cliMode) {
                $this->pid = 0;
            } else {
                $this->pid = $GLOBALS['TSFE']->id;
            }
        } else {
            $this->pid = $GLOBALS['TSFE']->id;
        }
    }

    /**
     * @param \TYPO3\CMS\Core\Html\RteHtmlParser $rteHtmlParser
     */
    public function injectRteHtmlParser(\TYPO3\CMS\Core\Html\RteHtmlParser $rteHtmlParser) {
        $this->rteHtmlParser = $rteHtmlParser;
    }

    /**
     * @param string $value
     * @param string $table
     * @param string $field
     *
     * @return string
     */
    public function transformDbToRte($value, $table, $field) {
        return $this->initRteHtmlParser($table, $field)->RTE_transform($value, 'rte');
    }

    /**
     * @param string $value
     * @param string $direction
     *
     * @return string
     */
    protected function RTE_transform($value, $direction) {
        if (TYPO3_MODE === 'BE') {
            $RTEsetup = $GLOBALS['BE_USER']->getTSConfig('RTE', BackendUtility::getPagesTSconfig($this->pid));

            $thisConfig = BackendUtility::RTEsetup($RTEsetup['properties'], $this->table, $this->field, 'text');
        } else {
            $pageTSconfig = $GLOBALS['TSFE']->getPagesTSconfig();

            $thisConfig = $pageTSconfig['RTE.']['default.']['FE.'];
        }

        return $this->rteHtmlParser->RTE_transform(
            $value,
            [
                'richtext' => 1,
                'rte_transform' => [
                    'parameters' => ['mode=ts_css'],
                ],
            ],
            $direction,
            $thisConfig
        );
    }

    /**
     * @param string $table
     * @param string $field
     *
     * @return \Dagou\DagouExtbase\Service\RteService
     */
    protected function initRteHtmlParser($table, $field) {
        $this->table = $table;
        $this->field = $field;

        $this->rteHtmlParser->init($table.':'.$field, $this->pid);
        $this->rteHtmlParser->setRelPath('');

        return $this;
    }

    /**
     * @param string $value
     * @param string $table
     * @param string $field
     *
     * @return string
     */
    public function transformRteToDb($value, $table, $field) {
        return $this->initRteHtmlParser($table, $field)->RTE_transform($value, 'db');
    }
}