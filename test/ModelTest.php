<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 08:55
 */

namespace GrBaseFrameworkTest;

use GrBaseFramework\AbstractModel;
use GrBaseFrameworkTest\Classes\Dumb\Dumb;
use GrBaseFrameworkTest\Classes\Dumb\DumbManager;
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
        $newDumb = new Dumb();
        $newDumb->setName("Dumb name");
        $newDumb->setCount(42);
        $newDumb->setTimestamp(1499937118);
        
        $newDumb->insert();
        
        $this->assertNotNull($newDumb->getId());
        
        $database = $this->getConnection()->getConnection();
        $results = $database->query(
            'SELECT * FROM ' . $newDumb->getTableName() . ' WHERE name = \'Dumb name\''
        )->fetchAll(\PDO::FETCH_CLASS, Dumb::class);
        
        $this->assertNotEmpty($results);
        $this->assertCount(1, $results);
        $this->assertEquals($newDumb->getCount(), $results[0]->getCount());
        $this->assertEquals($newDumb->getTimestamp(), $results[0]->getTimestamp());
    }
    
    /**
     * @expectedException \Exception
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
        $dumb->update();
        
        
        /**
         * @var Dumb $newDumb
         */
        $newDumb = DumbManager::find(1);
        
        $this->assertEquals('Philip', $newDumb->getName());
        $this->assertEquals(42, $newDumb->getCount());
        $this->assertEquals(123456789, $newDumb->getTimestamp());
    }
    
    /**
     * @expectedException \Exception
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
