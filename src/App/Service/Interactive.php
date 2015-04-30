<?php

namespace App\Service;

use App\Entity\Container as ContainerEntity;
use App\Exception\BadResponseException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class Interactive.
 */
class Interactive
{
    const QUIT = 'Quit';

    /**
     * @param array $containers
     *
     * @return ChoiceQuestion
     */
    public function commandListQuestionDelete(array $containers)
    {
        array_unshift($containers, self::QUIT);
        $question = new ChoiceQuestion(
            '<question>Please enter the index of container to delete :</question> ',
            $containers,
            0
        );
        $question->setErrorMessage('Your choice is not valid');

        return $question;
    }

    /**
     * @return Question
     */
    public function commandAddQuestionInteractive()
    {
        return new ConfirmationQuestion('<question>Is it an interactive container ?</question> ', false);
    }

    /**
     * @return Question
     */
    public function commandAddQuestionCommand()
    {
        $question = new Question('<question>Please enter the command to launch container (without: docker run ...) :</question> ');
        $question->setValidator(function ($answer) {
            if (empty($answer)) {
                throw new BadResponseException('Value is required');
            }

            return $answer;
        });
        $question->setMaxAttempts(2);

        return $question;
    }

    /**
     * @param OutputInterface $output
     * @param array           $inspect
     *
     * @return Table
     */
    public function commandInspectTable(OutputInterface $output, array $inspect)
    {
        $nbRows = 0;
        $table = new Table($output);
        $table->setHeaders(['Key', 'Value']);
        if (isset($inspect[Inspection::IMAGE])) {
            $table->addRow([Inspection::IMAGE, $inspect[Inspection::IMAGE]]);
            $nbRows++;
        }
        if (isset($inspect[Inspection::IP])) {
            $table->addRow([Inspection::IP, $inspect[Inspection::IP]]);
            $nbRows++;
        }
        if (isset($inspect[Inspection::MAC])) {
            $table->addRow([Inspection::MAC, $inspect[Inspection::MAC]]);
            $nbRows++;
        }
        if (isset($inspect[Inspection::PORTS])) {
            foreach ($inspect[Inspection::PORTS] as $port) {
                $nbRows++;
                $table->addRow([Inspection::PORTS, $port]);
            }
        }

        if (0 === $nbRows) {
            $table->addRow([new TableCell('No data', ['colspan' => 2])]);
        }

        return $table;
    }

    /**
     * @param OutputInterface $output
     * @param ContainerEntity $container
     *
     * @return Table
     */
    public function commandConfigTable(OutputInterface $output, ContainerEntity $container)
    {
        $table = new Table($output);
        $table->setHeaders(['Key', 'Value']);

        $table->addRow(['Interactive', $container->isInteractive() ? 'Yes' : 'No']);
        $table->addRow(['Command', $container->getCommand()]);

        return $table;
    }
}
