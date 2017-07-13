<?php


namespace GrBaseFramework;


/**
 * Class Manager
 *
 * @author Gaetan
 * @date   13/07/2017
 */
abstract class AbstractManager
{
    /**
     * Database instance.
     *
     * @var \PDO $database
     */
    public static $database;
    
    public static function find(int $id): AbstractModel
    {
        $statement = self::$database->prepare('SELECT * FROM ' . self::getTableName() ' WHERE id=:id');
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_CLASS, self::getModelName());
        
        if (empty($results)) return null;
        return $results[0];
    }
    
    /**
     * Return manager table name.
     *
     * For this to work, the following rules **must** be followed :
     * * A manager class name **must** use CamelCase;
     * * A manager class name **can** end with `Manager`;
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
    
    /**
     * Return the model name managed by this manager.
     *
     * For this to work, the following rules must be followed:
     * * The model and the manager class **must** be in the same namespace.
     * * The rules specified by `getTableName` in `AbstractManager` and `AbstractModel` **must** both be followed.
     *
     * @param bool $short If true, will only return the short name.
     * @return string
     */
    public static function getModelName(bool $short = true): string
    {
        $reflectionClass = new \ReflectionClass(static::class);
        $className = $reflectionClass->getShortName();
        $shortName =  str_replace('Manager', '', $className);
        
        if ($short) return $shortName;
        
        return $reflectionClass->getNamespaceName() . '\\' . $shortName;
    }
}