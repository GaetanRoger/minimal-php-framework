<?php


namespace GrBaseFrameworkTest;

use GrBaseFramework\AbstractManager;
use GrBaseFramework\AbstractModel;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;


/**
 * Class DatabaseTest
 *
 * @author Gaetan
 * @date   13/07/2017
 */
abstract class DatabaseTestBase extends TestCase
{
    use TestCaseTrait;
    
    protected function getConnection()
    {
        $config = require __DIR__ . '/config.php';
        
        $name = $config['database']['name'];
        $host = $config['database']['host'];
        $user = $config['database']['user'];
        $password = $config['database']['password'];
        
        $pdo = new \PDO("mysql:dbname=$name;host=$host", $user, $password);
        $connection = $this->createDefaultDBConnection($pdo, $name);
        
        AbstractModel::$database = $connection->getConnection();
        AbstractManager::$database = $connection->getConnection();
        
        return $connection;
    }
    
    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/data/database.xml');
    }
}