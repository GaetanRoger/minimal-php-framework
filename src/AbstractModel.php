<?php


namespace GrBaseFramework;


/**
 * Model class.
 *
 * @author Gaetan
 * @date   12/07/2017
 */
abstract class AbstractModel
{
    /**
     * Database instance.
     *
     * @var \PDO $database
     */
    public static $database;
    
    /**
     * Model ID.
     *
     * @var int $id
     */
    protected $id;
    
    /**
     * Return the model unique ID.
     *
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Return model table name.
     *
     * For this to work, the following rules **must** be followed :
     * * A model class name **must** use CamelCase;
     * * A model class name **can** end with `Model`;
     * * A model class name **must** not contain `Model` anywhere else;
     * * The table name **must** use snake_case;
     * * The model class name **must** be the exact conversion from snake_case to CamelCase of the table name.
     *
     * @return string
     */
    public function getTableName(): string
    {
        $reflectionClass = new \ReflectionClass($this);
        $className = $reflectionClass->getShortName();
        $name = str_replace('Model', '', $className);
        
        return Utils::camelCaseToSnakeCase($name);
    }
    
    /**
     * Insert model into database.
     *
     * @return AbstractModel
     */
    public function insert(): AbstractModel
    {
        $binding = [];
    
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        foreach ($properties as $property) {
            $property->setAccessible(true);
            if ($property->getName() === 'id' || $property->getValue($this) === null)
                continue;
        
            $value = $property->getValue($this);
            if ($value === true)
                $value = 1;
            else if ($value === false)
                $value = 0;
        
            $binding[$property->getName()] = $value;
        }
    
        $tableName = $this->getTableName();
        $columns = implode(', ', array_keys($binding));
        $valuesPlaceholders = ':' . implode(', :', array_keys($binding));
    
        $statement = self::$database->prepare("INSERT INTO $tableName ($columns) VALUES ($valuesPlaceholders)");
        $statement->execute($binding);
    
        $this->id = self::$database->lastInsertId();
        
        return $this;
    }
}