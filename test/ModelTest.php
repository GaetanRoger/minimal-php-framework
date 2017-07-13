<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 08:55
 */

namespace GrBaseFrameworkTest;

use GrBaseFramework\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testGetTableName()
    {
        $myNewModel = $this->getMockForAbstractClass(Model::class, [], "MyNewModel");
        $oneAgain = $this->getMockForAbstractClass(Model::class, [], "OneAgain");
        
        $myNewModelTableName = $myNewModel->getTableName();
        $oneAgainTableName = $oneAgain->getTableName();
        
        $this->assertEquals("my_new", $myNewModelTableName);
        $this->assertEquals("one_again", $oneAgainTableName);
    }
}
