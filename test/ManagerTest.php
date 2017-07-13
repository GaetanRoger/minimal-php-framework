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
use MongoDB\Driver\Manager;
use PHPUnit\Framework\TestCase;

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
}
