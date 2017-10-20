<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 08:55
 */

namespace Gaetanroger\MinimalPhpFrameworkTest;

use Gaetanroger\MinimalPhpFramework\AbstractModel;
use Gaetanroger\MinimalPhpFrameworkTest\Classes\Dumb\Dumb;
use Gaetanroger\MinimalPhpFrameworkTest\Classes\Dumb\DumbManager;
use PHPUnit\Exception;


class ModelTest extends DatabaseTestBase
{
    public function testGetTableName()
    {
        $myNewModel = $this->getMockForAbstractClass(AbstractModel::class, [], "MyNew");
        $oneAgain = $this->getMockForAbstractClass(AbstractModel::class, [], "OneAgain");
        $dumbModel = new Dumb();
        
        $myNewModelTableName = $myNewModel->getTableName();
        $oneAgainTableName = $oneAgain->getTableName();
        $dumbModelTableName = $dumbModel->getTableName();
        
        $this->assertEquals("my_new", $myNewModelTableName);
        $this->assertEquals("one_again", $oneAgainTableName);
        $this->assertEquals("dumb", $dumbModelTableName);
    }
    
    public function testInsert()
    {
        // Creating true
        $newDumbTrue = new Dumb();
        $newDumbTrue->setName("Dumb name true");
        $newDumbTrue->setCount(42);
        $newDumbTrue->setTimestamp(1499937118);
        $newDumbTrue->setBool(true);
        
        // Creating false
        $newDumbFalse = new Dumb();
        $newDumbFalse->setName("Dumb name false");
        $newDumbFalse->setCount(21);
        $newDumbFalse->setTimestamp(1499937119);
        $newDumbFalse->setBool(false);
        
        // Inserting both
        $newDumbTrue->insert();
        $newDumbFalse->insert();
        
        // Checking ID was set for both
        $this->assertNotNull($newDumbTrue->getId());
        $this->assertNotNull($newDumbFalse->getId());
        $this->assertNotEquals($newDumbTrue->getId(), $newDumbFalse->getId());
        
        /**
         * @var \PDO $database
         */
        $database = $this->getConnection()->getConnection();
        
        
        // Testing true
        
        /**
         * @var Dumb[] $resultsTrue
         */
        $resultsTrue = $database->query(
            'SELECT * FROM ' . $newDumbTrue->getTableName() . ' WHERE name = \'Dumb name true\''
        )->fetchAll(\PDO::FETCH_CLASS, Dumb::class);
        
        $this->assertNotEmpty($resultsTrue);
        $this->assertCount(1, $resultsTrue);
        $this->assertEquals($newDumbTrue->getCount(), $resultsTrue[0]->getCount());
        $this->assertEquals($newDumbTrue->getTimestamp(), $resultsTrue[0]->getTimestamp());
        $this->assertEquals($newDumbTrue->isBool(), $resultsTrue[0]->isBool());
        
        
        // Testing false
        
        
        /**
         * @var Dumb[] $resultsFalse
         */
        $resultsFalse = $database->query(
            'SELECT * FROM ' . $newDumbTrue->getTableName() . ' WHERE name = \'Dumb name false\''
        )->fetchAll(\PDO::FETCH_CLASS, Dumb::class);
        
        $this->assertNotEmpty($resultsFalse);
        $this->assertCount(1, $resultsFalse);
        $this->assertEquals($newDumbFalse->getCount(), $resultsFalse[0]->getCount());
        $this->assertEquals($newDumbFalse->getTimestamp(), $resultsFalse[0]->getTimestamp());
        $this->assertEquals($newDumbFalse->isBool(), $resultsFalse[0]->isBool());
        
        
    }
    
    /**
     * @expectedException \BadMethodCallException
     */
    public function testInsertWithNonNullId()
    {
        $dumb = DumbManager::find(1);
        
        $dumb->insert();
    }
    
    public function testUpdate()
    {
        /**
         * @var Dumb $dumb
         */
        $dumb = DumbManager::find(1);
        
        $dumb->setName('Philip');
        $dumb->setCount(42);
        $dumb->setTimestamp(123456789);
        $dumb->setBool(false);
        $dumb->update();
        
        
        /**
         * @var Dumb $newDumb
         */
        $newDumb = DumbManager::find(1);
        
        $this->assertEquals('Philip', $newDumb->getName());
        $this->assertEquals(42, $newDumb->getCount());
        $this->assertEquals(123456789, $newDumb->getTimestamp());
        $this->assertEquals(false, $newDumb->isBool());
    }
    
    /**
     * @expectedException \BadMethodCallException
     */
    public function testUpdateNotFound()
    {
        $dumb = new Dumb();
        
        $reflectionClass = new \ReflectionClass($dumb);
        $idProp = $reflectionClass->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($dumb, 999999);
        
        $dumb->update();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testUpdateWithNullId()
    {
        $dumb = new Dumb();
        $dumb->update();
    }
    
    /**
     * @expectedException Exception
     */
    public function testDelete()
    {
        $dumb = DumbManager::find(1);
        
        $dumb->delete();
        
        $this->assertNull($dumb->getId());
        $this->assertCount(199, DumbManager::findAll());
        DumbManager::find(1);
    }
    
    /**
     * @expectedException \Exception
     */
    public function testDeleteNotFound()
    {
        $dumb = new Dumb();
        
        $reflectionClass = new \ReflectionClass($dumb);
        $idProp = $reflectionClass->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($dumb, 999999);
        
        $dumb->delete();
    }
    
    /**
     * @expectedException \Exception
     */
    public function testDeleteWithNullId()
    {
        $dumb = new Dumb();
        $dumb->delete();
    }
}
