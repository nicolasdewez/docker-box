<?php

namespace App\Command;

use App\Entity\Container;
use App\Exception\InvalidArgumentException;
use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddContainerCommand.
 */
class AddContainerCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:add')
            ->setDescription('Add a container')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of container')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->container->get('app.configuration');
        if ($configuration->exists($input->getArgument('name'))) {
            throw new InvalidArgumentException('This container already exists', $this->getName());
        }

        $interactive = $this->container->get('app.interactive');
        $helper = $this->getHelper('question');

        $question = $interactive->commandAddQuestionCommand();
        $command = $helper->ask($input, $output, $question);

        $container = new Container();
        $container->setName($input->getArgument('name'));
        $container->setCommand($command);

        $configuration->save($container);

        $output->writeln('<info>Container added</info>');
    }
}
