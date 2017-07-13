<?php
/**
 * Project base-php-framework
 *
 * @author Gaetan
 * @date   13/07/2017 08:20
 */

namespace GrBaseFrameworkTest;

use GrBaseFramework\Utils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    
    public function testCamelCadeToSnakeCase()
    {
        $camelCase1 = "CamelCase";
        $camelCase2 = "UmlName";
        $camelCase3 = "ILoveLetters";
        $camelCase4 = "OneClassModel";
        
        $snakeCase1 = Utils::camelCaseToSnakeCase($camelCase1);
        $snakeCase2 = Utils::camelCaseToSnakeCase($camelCase2);
        $snakeCase3 = Utils::camelCaseToSnakeCase($camelCase3);
        $snakeCase4 = Utils::camelCaseToSnakeCase($camelCase4);
        
        $this->assertEquals('camel_case', $snakeCase1);
        $this->assertEquals('uml_name', $snakeCase2);
        $this->assertEquals('i_love_letters', $snakeCase3);
        $this->assertEquals('one_class_model', $snakeCase4);
    }
}
