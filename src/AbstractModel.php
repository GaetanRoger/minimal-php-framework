<?php


namespace Gaetanroger\MinimalPhpFramework;


/**
 * Model class.
 *
 * A model is a representation of a database entity. Each model is identified by its unsigned integer ID.
 * * All model attributes **must** correspond to their database fields, even if it makes use of snake_case in
 * attributes name.
 * * Getters and setters **should** use CamelCase, event it their properties use snake_case.
 * * Model name **must** be the exact conversion from snake_case to CamelCase of the table name.
 *
 * See *README.MD* for further rules and info.
 *
 * @author Gaetan
 * @date   12/07/2017
 */
abstract class AbstractModel
{
    /**
     * Database instance.
     *
     * This \PDO instance must be set before any model is used, as it is the center
     * of any database related operation.
     *
     * @var \PDO $database
     */
    public static $database;
    
    /**
     * Model unique ID.
     *
     * This ID is used as the primary key in all database operations. The model assumes that
     * the ID is in auto increment.
     *
     * @var int $id
     */
    protected $id;
    
    /**
     * Insert model into database.
     *
     * The model assumed the ID field is in auto increment, and will not be set in this method. This method
     * will actually use lastInsertId to set the model ID field to the corresponding value.
     *
     * @return AbstractModel The model itself, to be used in fluent operations.
     * @throws \Exception If getId does not return null (meaning the model is already inserted into database).
     */
    public function insert(): AbstractModel
    {
        // If the model is already inserted into database, throwing an exception
        if ($this->getId() !== null) {
            throw new \Exception(
                "This model already has an ID (meaning it is already inserted into database). Maybe use update?"
            );
        }
        
        // Name of field associated with value.
        $binding = [];
        
        // Getting properties
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        
        
        // For each property of the object
        foreach ($properties as $property) {
            
            // Allowing us to access private values
            $property->setAccessible(true);
            
            // If the value is null, no need to include it in the query (default values are used)
            if ($property->getValue($this) === null) {
                continue;
            }
            
            // Converting boolean to MySQL ones & zeros TINYINT
            $value = $property->getValue($this);
            if ($value === true) {
                $value = 1;
            } else if ($value === false) {
                $value = 0;
            }
            
            // Binding property name to value
            $binding[$property->getName()] = $value;
        }
        
        // Getting table name
        $tableName = $this->getTableName();
        
        // Getting table columns from class attributes
        $columns = implode(', ', array_keys($binding));
        
        // Getting placeholders by adding `:` in front of columns name
        $valuesPlaceholders = ':' . implode(', :', array_keys($binding));
        
        // Running query
        $statement = self::$database->prepare("INSERT INTO $tableName ($columns) VALUES ($valuesPlaceholders)");
        $statement->execute($binding);
        
        // Getting ID
        $this->id = self::$database->lastInsertId();
        
        // Returning $this to allow fluent usage
        return $this;
    }
    
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
        // Getting the class name
        $reflectionClass = new \ReflectionClass($this);
        $className = $reflectionClass->getShortName();
        
        // Convert to snake_case
        return Utils::camelCaseToSnakeCase($className);
    }
    
    /**
     * Update model in database.
     *
     * The method will use the model ID to update all its other fields.
     *
     * @return AbstractModel The model itself, to be used in fluent operations.
     * @throws \Exception If model ID is null (meaning it is not inserted into database).
     */
    public function update(): AbstractModel
    {
        // If the model is not inserted into database, throwing an exception
        if ($this->getId() === null) {
            throw new \Exception("Model ID is null (meaning it's not inserted into database). Maybe use insert?");
        }
        
        
        // Getting class properties
        $reflectionClass = new \ReflectionClass($this);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        
        // Array of placeholders (columns with `:` before)
        $placeholders = [];
        
        // Name of field associated with value.
        $binding = [];
        
        
        // For each property
        foreach ($properties as $property) {
            
            // If property is the ID, skipping adding it to params
            if ($property->getName() === 'id') {
                continue;
            }
            
            // Allowing us to access private values
            $property->setAccessible(true);
            
            // Values to be places after SET
            $placeholders[] = $property->getName() . ' = :' . $property->getName();
            
            // Binding property name to value
            $binding[':' . $property->getName()] = $property->getValue($this);
        }
        
        
        // Adding the ID to the bindings
        $binding = array_merge($binding, [':id' => $this->getId()]);
        
        // Running query
        $statement = self::$database->prepare(
            'UPDATE ' . $this->getTableName() .
            ' SET ' . implode(', ', $placeholders) .
            ' WHERE id = :id'
        );
        $statement->execute($binding);
        
        // If unexpected rows were modified (or not), throwing an exception
        if ($statement->rowCount() !== 1) {
            throw new \Exception('Row count does not equal one (found ' . $statement->rowCount() . ').');
        }
        
        // Returning $this to allow fluent usage
        return $this;
    }
    
    /**
     * Delete model in database.
     *
     * This method will use the model ID to delete it from database. The model ID will then be set
     * to null.
     *
     * @return AbstractModel The model itself, to be used in fluent operations.
     * @throws \Exception If modified row count does not equal one.
     * @throws \Exception If ID is null (meaning that the model is not inserted into database).
     */
    public function delete(): AbstractModel
    {
        // If the model is not inserted into database, throwing an exception
        if ($this->getId() === null) {
            throw new \Exception("Model ID is null (meaning it's not inserted into database).");
        }
        
        // Running query
        $statement = self::$database->prepare('DELETE FROM ' . $this->getTableName() . ' WHERE id = :id');
        $statement->bindValue(':id', $this->getId(), \PDO::PARAM_INT);
        $statement->execute();
        
        // If unexpected rows were deleted (or not), throwing an exception
        if ($statement->rowCount() !== 1) {
            throw new \Exception('Row count does not equal one (found ' . $statement->rowCount() . ').');
        }
        
        // Resetting this ID to null
        $this->id = null;
        
        // Returning $this to allow fluent usage
        return $this;
    }
}