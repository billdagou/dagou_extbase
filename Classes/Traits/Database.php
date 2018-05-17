<?php
namespace Dagou\DagouExtbase\Traits;

use TYPO3\CMS\Core\Database\ConnectionPool;

trait Database {
    /**
     * @var \TYPO3\CMS\Core\Database\ConnectionPool
     */
    protected $connectionPool;
    /**
     * @var string
     */
    protected $tableName;
    /**
     * @var string
     */
    protected $columnName;
    /**
     * @var array
     */
    static protected $connections = [];

    /**
     * @param \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool
     */
    public function injectConnectionPool(ConnectionPool $connectionPool) {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @return mixed
     */
    protected function getConnection() {
        if (!static::$connections[$this->tableName]) {
            static::$connections[$this->tableName] = $this->connectionPool->getConnectionForTable($this->tableName);
        }

        return static::$connections[$this->tableName];
    }
}