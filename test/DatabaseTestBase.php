<?php


namespace GrBaseFrameworkTest;

use GrBaseFramework\AbstractManager;
use GrBaseFramework\AbstractModel;
use PHPUnit\DbUnit\Database\DefaultConnection;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;


/**
 * Test class used as parent when tests need to access database.
 *
 * This class use the data in file ./data/database.xml to provide a dummy table to the tests.
 *
 * @author Gaetan
 * @date   13/07/2017
 */
abstract class DatabaseTestBase extends TestCase
{
    use TestCaseTrait;
    
    /**
     * @var \PDO $pdo
     */
    static private $pdo = null;
    
    /**
     * @var DefaultConnection $connection
     */
    private $connection = null;
    
    private function _initDatabase()
    {
        $this->connection->getConnection()->query('DROP TABLE IF EXISTS dumb');
        
        $this->connection->getConnection()->query(
            "CREATE TABLE dumb (
                      id INTEGER PRIMARY KEY AUTOINCREMENT,
                      name VARCHAR(255),
                      count INT,
                      timestamp TIMESTAMP
                      )"
        );
    }
    
    
    /**
     * @inheritdoc
     */
    protected function getConnection()
    {
        if ($this->connection === null) {
            if (self::$pdo == null) {
                self::$pdo = new \PDO('sqlite::memory:');
            }
            
            $this->connection = $this->createDefaultDBConnection(self::$pdo, "db");
            
            AbstractModel::$database = $this->connection->getConnection();
            AbstractManager::$database = $this->connection->getConnection();
            
            $this->_initDatabase();
        }
        
        return $this->connection;
    }
    
    /**
     * @inheritdoc
     */
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/data/database.xml');
    }
}