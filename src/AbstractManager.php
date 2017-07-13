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
    
    /**
     * @param int $id
     * @return AbstractModel
     */
    public static function find(int $id): AbstractModel
    {
        $results = static::findWhere(['id' => $id]);
        
        if (empty($results))
            throw new \InvalidArgumentException("Model not found with id $id.");
        
        return $results[0];
    }
    
    /**
     * @return AbstractModel[]
     */
    public static function findAll(): array
    {
        return static::findWhere([]);
    }
    
    /**
     * @param array $conditions
     * @return AbstractModel[]
     */
    public static function findWhere(array $conditions): array
    {
        $conditionsCount = count($conditions);
    
        if ($conditionsCount === 0) {
            return
                static::$database->query('SELECT * FROM ' . static::getTableName())
                ->fetchAll(\PDO::FETCH_CLASS, self::getModelName(false));
        }
        
        
        $where = ' WHERE';
        $columns = array_keys($conditions);
        $values = array_values($conditions);
        
        for ($i = 0; $i < $conditionsCount; ++$i) {
            $where .= ' ' . $columns[$i] . ' = :' . $columns[$i];
            
            if ($i < $conditionsCount - 1)
                $where .= ' AND';
        }
        
        $statement = static::$database->prepare('SELECT * FROM ' . static::getTableName() . $where);
    
        for ($i = 0; $i < $conditionsCount; ++$i) {
            $statement->bindValue(':' . $columns[$i], $values[$i]);
        }
        
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_CLASS, static::getModelName(false));
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