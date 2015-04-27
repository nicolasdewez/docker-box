<?php

namespace App\Command;

use App\Exception\InvalidArgumentException;
use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StatusContainerCommand.
 */
class StatusContainerCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:status')
            ->setDescription('Status of container')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of container')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if (!$this->container->get('app.configuration')->exists($name)) {
            throw new InvalidArgumentException('This container doesn\'t exists', $this->getName());
        }

        $status = $this->container->get('app.container')->status($name);
        $display = sprintf('<info>Container %s is %s</info>', $name, $status);

        $output->writeln($display);
    }
}
