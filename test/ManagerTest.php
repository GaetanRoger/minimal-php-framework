<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 14:23
 */

namespace GrBaseFrameworkTest;

use GrBaseFramework\AbstractManager;
use GrBaseFrameworkTest\Classes\Dumb\Dumb;
use GrBaseFrameworkTest\Classes\Dumb\DumbManager;

class ManagerTest extends DatabaseTestBase
{
    
    public function testGetTableName()
    {
        $myNewManager = $this->getMockForAbstractClass(AbstractManager::class, [], "MyNewManager");
        $oneAgainManager = $this->getMockForAbstractClass(AbstractManager::class, [], "OneAgainManager");
        
        $myNewManagerTableName = $myNewManager::getTableName();
        $oneAgainManagerTableName = $oneAgainManager::getTableName();
        $dumbManagerTableName = DumbManager::getTableName();
        
        $this->assertEquals("my_new", $myNewManagerTableName);
        $this->assertEquals("one_again", $oneAgainManagerTableName);
        $this->assertEquals("dumb", $dumbManagerTableName);
    }
    
    public function testFind()
    {
        /**
         * @var Dumb $dumb
         */
        $dumb = DumbManager::find(1);
        
        $this->assertNotNull($dumb);
        $this->assertEquals('Aksel', $dumb->getName());
        $this->assertEquals(444, $dumb->getCount());
        $this->assertEquals(1497562479, $dumb->getTimestamp());
    }
    
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidFind()
    {
        DumbManager::find(99999);
    }
    
    public function testFindAll()
    {
        $dumbs = DumbManager::findAll();
        
        $this->assertCount(200, $dumbs);
    }
    
    public function testFindWhere()
    {
        /**
         * @var Dumb[] $dumbs
         */
        $dumbs = DumbManager::findWhere(['id' => 1]);
        
        $this->assertCount(1, $dumbs);
        $this->assertEquals('Aksel', $dumbs[0]->getName());
        
        
        $dumbs = DumbManager::findWhere(['count' => 830]);
        
        $this->assertCount(2, $dumbs);
        $this->assertEquals('Annabella', $dumbs[0]->getName());
        $this->assertEquals('Neda', $dumbs[1]->getName());
        
        
        $dumbs = DumbManager::findWhere(['count' => 999999]);
        
        $this->assertCount(0, $dumbs);
    }
}
