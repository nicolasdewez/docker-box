<?php

namespace App\Service;

use App\Exception\ProcessException;

/**
 * Class Container.
 */
class Container
{
    const STARTED = 'Started';
    const STOPPED = 'Stopped';

    /** @var Configuration */
    protected $configuration;

    /** @var Inspection */
    protected $inspection;

    /** @var Docker */
    protected $docker;

    /**
     * @param Configuration $configuration
     * @param Inspection    $inspection
     * @param Docker        $docker
     */
    public function __construct(Configuration $configuration, Inspection $inspection, Docker $docker)
    {
        $this->configuration = $configuration;
        $this->inspection = $inspection;
        $this->docker = $docker;
    }

    /**
     * @param string $name
     */
    public function initialize($name)
    {
        $container = $this->configuration->load($name);
        $this->docker->initialize($container);
    }

    /**
     * @param string $name
     */
    public function start($name)
    {
        if (!$this->docker->exists($name)) {
            $this->initialize($name);
        }

        $this->docker->start($name);
    }

    /**
     * @param string $name
     *
     * @throws ProcessException
     */
    public function stop($name)
    {
        if (!$this->docker->exists($name, true)) {
            throw new ProcessException('Container is already stop');
        }

        $this->docker->stop($name);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function status($name)
    {
        return $this->docker->exists($name, true) ? self::STARTED : self::STOPPED;
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return array
     */
    public function inspect($name, $field)
    {
        $output = $this->docker->inspect($name);

        return $this->inspection->get($output, $field);
    }

    /**
     * @param string $name
     */
    public function delete($name)
    {
        if ($this->docker->exists($name, true)) {
            $this->docker->stop($name);
        }

        if ($this->docker->exists($name)) {
            $this->docker->delete($name);
        }

        $this->configuration->delete($name);
    }
}
