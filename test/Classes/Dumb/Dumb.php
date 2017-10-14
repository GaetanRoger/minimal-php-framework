<?php


namespace Gaetanroger\MinimalPhpFrameworkTest\Classes\Dumb;

use Gaetanroger\MinimalPhpFramework\AbstractModel;


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
     * @var bool $bool
     */
    private $bool;
    
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
    
    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->bool;
    }
    
    /**
     * @param bool $bool
     */
    public function setBool(bool $bool)
    {
        $this->bool = $bool;
    }
    
    
}