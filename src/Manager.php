<?php


namespace GrBaseFramework;


/**
 * Class Manager
 *
 * @author Gaetan
 * @date   13/07/2017
 */
abstract class Manager
{
    /**
     * Database instance.
     *
     * @var \PDO $database
     */
    public static $database;
    
    
    /**
     * Return manager table name.
     *
     * For this to work, the following rules **must** be followed :
     * * A manager class name **must** use CamelCase;
     * * A manager class name **must** end with `Manager`;
     * * A model class name **must** not contain `Manager` anywhere else;
     * * The table name **must** use snake_case;
     * * The manager class name **must** be the exact conversion from snake_case to CamelCase of the table name.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $className = $reflectionClass->getShortName();
        $name = str_replace('Manager', '', $className);
    
        return Utils::camelCaseToSnakeCase($name);
    }
}