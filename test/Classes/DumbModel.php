<?php


namespace GrBaseFrameworkTest\Classes;

use GrBaseFramework\Model;


/**
 * Class DumbModel
 *
 * @author Gaetan
 * @date   13/07/2017
 */
class DumbModel extends Model
{
    /**
     * @var string $name
     */
    private $name;
    
    /**
     * @var int $count
     */
    private $count;
    
    /**
     * @var int $timestamp
     */
    private $timestamp;
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
    
    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
    
    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }
    
    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }
    
    
}