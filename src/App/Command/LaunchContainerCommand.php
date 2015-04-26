<?php

namespace App\Command;

use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LaunchContainerCommand.
 */
class LaunchContainerCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:launch')
            ->setDescription('Launch a container')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
