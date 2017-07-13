<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 14:23
 */

namespace GrBaseFrameworkTest;

use GrBaseFramework\AbstractManager;
use GrBaseFrameworkTest\Classes\Dumb\DumbManager;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
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
}
