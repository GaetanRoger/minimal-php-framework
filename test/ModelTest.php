<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 08:55
 */

namespace GrBaseFrameworkTest;

use GrBaseFramework\AbstractModel;
use GrBaseFrameworkTest\Classes\DumbModel;

class ModelTest extends DatabaseTestBase
{
    public function testGetTableName()
    {
        $myNewModel = $this->getMockForAbstractClass(AbstractModel::class, [], "MyNewModel");
        $oneAgain = $this->getMockForAbstractClass(AbstractModel::class, [], "OneAgain");
        $dumbModel = new DumbModel();
        
        $myNewModelTableName = $myNewModel->getTableName();
        $oneAgainTableName = $oneAgain->getTableName();
        $dumbModelTableName = $dumbModel->getTableName();
        
        $this->assertEquals("my_new", $myNewModelTableName);
        $this->assertEquals("one_again", $oneAgainTableName);
        $this->assertEquals("dumb", $dumbModelTableName);
    }
    
    public function testInsert()
    {
        $newDumb = new DumbModel();
        $newDumb->setName("Dumb name");
        $newDumb->setCount(42);
        $newDumb->setTimestamp(1499937118);
        
        $newDumb->insert();
        
        $this->assertNotNull($newDumb->getId());
        
        $database = $this->getConnection()->getConnection();
        $results = $database->query(
            'SELECT * FROM ' . $newDumb->getTableName() . ' WHERE name = \'Dumb name\''
        )->fetchAll(\PDO::FETCH_CLASS, DumbModel::class);
        
        $this->assertNotEmpty($results);
        $this->assertCount(1, $results);
        $this->assertEquals($newDumb->getCount(), $results[0]->getCount());
        $this->assertEquals($newDumb->getTimestamp(), $results[0]->getTimestamp());
    }
}
