<?php

namespace App\Entity;

/**
 * Class Container.
 */
class Container
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $command;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
