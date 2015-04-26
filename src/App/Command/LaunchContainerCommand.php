<?php

namespace App\Command;

use App\Exception\InvalidArgumentException;
use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addArgument('name', InputArgument::REQUIRED, 'Name of container')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->container->get('app.configuration')->exists($input->getArgument('name'))) {
            throw new InvalidArgumentException('This container doesn\'t exists', $this->getName());
        }

        // TODO : see multiple

        if ($this->container->get('app.container')->start($input->getArgument('name'))) {
            $output->writeln('<info>Container launched</info>');
        } else {
            $output->writeln('<error>A problem is occured</error>');
        }
    }
}
