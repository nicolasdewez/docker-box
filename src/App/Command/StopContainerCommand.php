<?php

namespace App\Command;

use App\Exception\InvalidArgumentException;
use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addArgument('name', InputArgument::REQUIRED, 'Name of container')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->container->get('app.configuration')->exists($input->getArgument('name'))) {
            throw new InvalidArgumentException('This container doesn\'t exists');
        }

        $this->container->get('app.container')->stop($input->getArgument('name'));
        $output->writeln('<info>Container stopped</info>');
    }
}
