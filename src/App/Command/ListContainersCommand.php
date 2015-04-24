<?php

namespace App\Command;

use App\Service\Interactive;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListContainersCommand.
 */
class ListContainersCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:list')
            ->setDescription('List containers defined')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');
        $configuration = $this->container->get('app.configuration');
        $container = $this->container->get('app.container');
        $interactive = $this->container->get('app.interactive');
        do {
            $containers = $configuration->lists(false);
            $question = $interactive->commandListQuestionDelete($containers);
            $response = $questionHelper->ask($input, $output, $question);
            $container->delete($response);
        } while (count($containers) && Interactive::QUIT !== $response);
    }
}
