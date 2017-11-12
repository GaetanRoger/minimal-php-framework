<?php


namespace Gaetanroger\MinimalPhpFramework;


/**
 * Manager class.
 *
 * A manager defines all static methods not specific to one model.
 * Please see *README.MD* for naming conventions to follow.
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
     * Find the model associated with the given ID.
     *
     * @param   int $id ID of the model to find.
     * @return  AbstractModel
     * @throws  \InvalidArgumentException If nothing was found.
     */
    public static function find(int $id): AbstractModel
    {
        // Finding all models matching the ID (there should be only one)
        $results = static::findWhere(['id' => $id]);
        
        // Throwing an exception if none was found
        if (empty($results)) {
            throw new \InvalidArgumentException("Model not found with id $id.");
        }
        
        // Returning the model
        return $results[0];
    }
    
    /**
     * Find models matching conditions.
     *
     * All the conditions are strict equality checks (`=` sign).
     *
     * @param array $conditions Associative array with key being the column and value being the value to test.
     * @return AbstractModel[] Found models.
     */
    public static function findWhere(array $conditions): array
    {
        // Counting conditions
        $conditionsCount = count($conditions);
        
        // If no conditions given, perform a simple SELECT * on the table
        if ($conditionsCount === 0) {
            return
                static::$database->query('SELECT * FROM ' . static::getTableName())
                    ->fetchAll(\PDO::FETCH_CLASS, self::getModelName(false));
        }
        
        // WHERE conditions
        $where = ' WHERE';
        
        // Array of columns
        $columns = array_keys($conditions);
        
        // Array of values
        $values = array_values($conditions);
        
        
        // Foreach condition
        for ($i = 0; $i < $conditionsCount; ++$i) {
            
            // Appending it to the where string
            $where .= ' ' . $columns[$i] . ' = :' . $columns[$i];
            
            // Adding AND keyword at the end
            if ($i < $conditionsCount - 1) {
                $where .= ' AND';
            }
        }
        
        
        // Preparing query
        $statement = static::$database->prepare('SELECT * FROM ' . static::getTableName() . $where);
        
        // Binding values
        for ($i = 0; $i < $conditionsCount; ++$i) {
            $statement->bindValue(':' . $columns[$i], $values[$i]);
        }
        
        // Running query
        $statement->execute();
        
        return $statement->fetchAll(\PDO::FETCH_CLASS, static::getModelName(false));
    }
    
    /**
     * Return manager table name.
     *
     * For this to work, the following rules **must** be followed :
     * * A manager class name **must** use CamelCase;
     * * A manager class name **must** end with `Manager`;
     * * A manager class name **must** not contain `Manager` anywhere else;
     * * The manager class name **must** be identical with the model name plus the word `Manager`.
     *
     * @return string
     */
    public static function getTableName(): string
    {
        // Getting class name
        $reflectionClass = new \ReflectionClass(static::class);
        $className = $reflectionClass->getShortName();
        
        // Removing `Manager` keyword
        $name = str_replace('Manager', '', $className);
        
        // Converting to snake_case
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
        // Getting class name
        $reflectionClass = new \ReflectionClass(static::class);
        $className = $reflectionClass->getShortName();
        
        // Removing `Manager` keyword
        $shortName = str_replace('Manager', '', $className);
        
        // If short, returning the name
        if ($short) {
            return $shortName;
        }
        
        // Else adding namespace
        return $reflectionClass->getNamespaceName() . '\\' . $shortName;
    }
    
    /**
     * Find all models.
     *
     * @return AbstractModel[]
     */
    public static function findAll(): array
    {
        return static::findWhere([]);
    }
    
    /**
     * Count lines in table using SQL `COUNT`.
     *
     * Default column parameter `*` is used to count all lines without targeting specific column. It is
     * the only value which is not a valid column name that will work. Be careful, using `*` and distinct to true
     * will throw an exception.
     *
     * @param string $column   Column to count.
     * @param bool   $distinct True to count only distinct values when targeting a specific column.
     * @return int Result of count.
     * @throws \InvalidArgumentException if `column` is set to `*` and `distinct` is set to true.
     */
    public static function count(string $column = '*', bool $distinct = false): int
    {
        if ($distinct && $column === '*') {
            throw new \InvalidArgumentException("Cannot use keyword DISTINCT with COUNT(*).");
        }
        
        $tableName = self::getTableName();
        $distinctStr = $distinct ? ' DISTINCT ' : '';
        
        $statement = static::$database->query("SELECT COUNT($distinctStr $column) FROM $tableName");
        
        return $statement->fetch()[0];
    }
}