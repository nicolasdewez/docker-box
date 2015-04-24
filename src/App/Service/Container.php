<?php

namespace App\Service;

use Symfony\Component\Process\Process;

/**
 * Class Container.
 */
class Container
{
    /** @var Configuration */
    protected $configuration;

    /**
     * @param Configuration $configuration
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function start($name)
    {
        $container = $this->configuration->load($name);
        $process = $this->buildProcess($container->getCommand());
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
        return true;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function status($name)
    {
        return true;
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
