<?php

namespace App\Service;

use App\Entity\Container as ContainerEntity;
use App\Exception\ProcessException;
use Symfony\Component\Process\Process;

/**
 * Class Docker.
 */
class Docker
{
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
     * @param ContainerEntity $container
     *
     * @return bool
     */
    public function initialize(ContainerEntity $container)
    {
        $command = sprintf('docker run --name %s %s', $container->getName(), $container->getCommand());
        $process = $this->buildProcess($command, $container->isInteractive());

        $this->runAndAnalyzeProcess($process);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function start($name)
    {
        $command = sprintf('docker start %s', $name);
        $process = $this->buildProcess($command, true);
        $this->runAndAnalyzeProcess($process);
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
        $this->runAndAnalyzeProcess($process);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function delete($name)
    {
        $command = sprintf('docker rm %s', $name);
        $process = $this->buildProcess($command);
        $this->runAndAnalyzeProcess($process);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function inspect($name)
    {
        $command = sprintf('docker inspect %s', $name);
        $process = $this->buildProcess($command);
        $this->runAndAnalyzeProcess($process);

        return $process->getOutput();
    }

    /**
     * @param string $command
     * @param bool   $interactive
     *
     * @return Process
     */
    protected function buildProcess($command, $interactive = false)
    {
        $process = new Process($command);
        $process->setTimeout(null);

        if ($interactive && !defined('PHP_WINDOWS_VERSION_BUILD') && php_sapi_name() === 'cli') {
            $process->setTty(true);
        }

        return $process;
    }

    /**
     * @param Process $process
     *
     * @throws ProcessException
     */
    protected function runAndAnalyzeProcess(Process $process)
    {
        $process->run();
        if (!$process->isSuccessful()) {
            $display = sprintf('An error is occured with docker command : %s', $process->getCommandLine());
            throw new ProcessException($display);
        }
    }
}
