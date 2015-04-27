<?php

namespace App\Service;

use Symfony\Component\Process\Process;

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

    /**
     * @param Configuration $configuration
     * @param Inspection    $inspection
     */
    public function __construct(Configuration $configuration, Inspection $inspection)
    {
        $this->configuration = $configuration;
        $this->inspection = $inspection;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function initialize($name)
    {
        $container = $this->configuration->load($name);

        $command = sprintf('docker run --name %s %s', $name, $container->getCommand());
        $process = $this->buildProcess($command);
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function start($name)
    {
        if (!$this->exists($name)) {
            return $this->initialize($name);
        }

        $command = sprintf('docker start %s', $name);
        $process = $this->buildProcess($command);
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function stop($name)
    {
        $command = sprintf('docker stop %s', $name);
        $process = $this->buildProcess($command);
        $process->run();

        return $process->isSuccessful();
    }

    /**
     * @param string $name
     * @param bool   $launched
     *
     * @return bool
     */
    public function exists($name, $launched = false)
    {
        $option = '-a';
        if (true === $launched) {
            $option = '';
        }

        $command = sprintf('docker ps %s |grep %s', $option, $name);
        $process = $this->buildProcess($command);
        $process->run();
        if (!$process->isSuccessful()) {
            return false;
        }

        $lines = explode(PHP_EOL, $process->getOutput());
        foreach ($lines as $line) {
            if (preg_match('#'.$name.'\s*$#', $line)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function status($name)
    {
        return $this->exists($name, true) ? self::STARTED : self::STOPPED;
    }

    /**
     * @param string $name
     * @param string $field
     *
     * @return array
     */
    public function inspect($name, $field)
    {
        $command = sprintf('docker inspect %s', $name);
        $process = $this->buildProcess($command);
        $process->run();
        if (!$process->isSuccessful()) {
            return [];
        }

        return $this->inspection->get($process->getOutput(), $field);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        return $this->stop($name) && $this->configuration->delete($name);
    }

    /**
     * @param string $command
     *
     * @return Process
     */
    protected function buildProcess($command)
    {
        return new Process($command);
    }
}
