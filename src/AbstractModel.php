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
     * * A model class name **must not** end with `Model`;
     * * The table name **must** use snake_case;
     * * The model class name **must** be the exact conversion from snake_case to CamelCase of the table name.
     *
     * @return string
     */
    public function getTableName(): string
    {
        $reflectionClass = new \ReflectionClass($this);
        $className = $reflectionClass->getShortName();
        
        return Utils::camelCaseToSnakeCase($className);
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
            if ($property->getName() === 'id' || $property->getValue($this) === null) {
                continue;
            }
            
            $value = $property->getValue($this);
            if ($value === true) {
                $value = 1;
            } else if ($value === false) {
                $value = 0;
            }
            
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
    
    public function update(): AbstractModel
    {
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        
        $placeholders = [];
        $values = [];
        
        foreach ($properties as $property) {
            if ($property->getName() === 'id') {
                continue;
            }
            
            $property->setAccessible(true);
            
            $placeholders[] = $property->getName() . ' = :' . $property->getName();
            $values[':' . $property->getName()] = $property->getValue($this);
        }
        
        $values = array_merge($values, [':id' => $this->getId()]);
        
        $statement = self::$database->prepare(
            'UPDATE ' . $this->getTableName() .
            ' SET ' . implode(', ', $placeholders) .
            ' WHERE id = :id'
        );
        $statement->execute($values);
        
        if ($statement->rowCount() !== 1) {
            throw new \Exception('Row count does not equal one (found ' . $statement->rowCount() . ').');
        }
        
        return $this;
        
    }
    
    public function delete(): AbstractModel
    {
        $statement = self::$database->prepare('DELETE FROM ' . $this->getTableName() . ' WHERE id = :id');
        $statement->bindValue(':id', $this->getId(), \PDO::PARAM_INT);
        $statement->execute();
    
        if ($statement->rowCount() !== 1) {
            throw new \Exception('Row count does not equal one (found ' . $statement->rowCount() . ').');
        }
        
        $this->id = null;
        
        return $this;
    }
}