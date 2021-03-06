<?php

namespace App\Command;

use App\Service\Configuration;
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
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of container', Configuration::ALL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $containerSrv = $this->container->get('app.container');

        $containers = $this->container->get('app.configuration')->loadInArray($input->getArgument('name'));
        foreach ($containers as $container) {
            $status = $containerSrv->status($container->getName());

            $display = sprintf('<info>Container %s is %s</info>', $container->getName(), $status);
            $output->writeln($display);
        }
    }
}
