<?php

namespace App\Command;

use App\Exception\InvalidArgumentException;
use App\Service\Inspection;
use Ndewez\ApplicationConsoleBundle\Command\ContainerCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InspectContainerCommand.
 */
class InspectContainerCommand extends ContainerCommand
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('container:inspect')
            ->setDescription('Inspect a container')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of container')
            ->addArgument('field', InputArgument::OPTIONAL, 'Field to inspect', Inspection::ALL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if (!$this->container->get('app.configuration')->exists($name)) {
            throw new InvalidArgumentException('This container doesn\'t exists');
        }

        $inspect = $this->container->get('app.container')->inspect($name, $input->getArgument('field'));
        $table = $this->container->get('app.interactive')->commandInspectTable($output, $inspect);
        $table->render();
    }
}
