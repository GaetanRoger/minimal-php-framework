<?php


namespace GrBaseFramework;


/**
 * Model class.
 *
 * @author Gaetan
 * @date   12/07/2017
 */
abstract class Model
{
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
    
    
}