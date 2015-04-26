<?php

namespace App\Command;

use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StopContainerCommand.
 */
class StopContainerCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:stop')
            ->setDescription('Stop a container')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
