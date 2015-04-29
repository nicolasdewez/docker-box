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
    const ALL = '[all]';

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:status')
            ->setDescription('Status of container')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of container', self::ALL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $configuration = $this->container->get('app.configuration');
        $containerSrv = $this->container->get('app.container');

        $containers = $configuration->lists();
        if (self::ALL !== $name) {
            if (!$configuration->exists($name)) {
                throw new InvalidArgumentException('This container doesn\'t exists', $this->getName());
            }

            $containers = [$name => $configuration->load($name)];
        }

        foreach ($containers as $container) {
            $status = $containerSrv->status($container->getName());

            $display = sprintf('<info>Container %s is %s</info>', $container->getName(), $status);
            $output->writeln($display);
        }
    }
}
