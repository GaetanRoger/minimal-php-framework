<?php


namespace GrBaseFrameworkTest\Classes\Dumb;

use GrBaseFramework\AbstractModel;


/**
 * Class DumbModel
 *
 * @author Gaetan
 * @date   13/07/2017
 */
class Dumb extends AbstractModel
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
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
    
    /**
     * @param int $count
     */
    public function setCount(int $count)
    {
        $this->count = $count;
    }
    
    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
    
    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp)
    {
        $this->timestamp = $timestamp;
    }
    
    
}