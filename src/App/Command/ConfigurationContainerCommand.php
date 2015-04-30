<?php

namespace App\Command;

use App\Service\Configuration;
use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConfigurationContainerCommand.
 */
class ConfigurationContainerCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:config')
            ->setDescription('Configuration of container')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of container', Configuration::ALL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $interactive = $this->container->get('app.interactive');
        $containers = $this->container->get('app.configuration')->loadInArray($input->getArgument('name'));
        foreach ($containers as $container) {
            $output->writeln(sprintf('<info>Container %s</info>', $container->getName()));
            $table = $interactive->commandConfigTable($output, $container);
            $table->render();
            $output->writeln('');
        }
    }
}
