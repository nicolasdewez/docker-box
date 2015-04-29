<?php

namespace App\Service;

use App\Exception\BadResponseException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
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
        $table = new Table($output);
        $table->setHeaders(['Key', 'Value']);
        if (isset($inspect[Inspection::IP])) {
            $table->addRow([Inspection::IP, $inspect[Inspection::IP]]);
        }
        if (isset($inspect[Inspection::MAC])) {
            $table->addRow([Inspection::MAC, $inspect[Inspection::MAC]]);
        }
        if (isset($inspect[Inspection::PORTS])) {
            foreach ($inspect[Inspection::PORTS] as $port) {
                $table->addRow([Inspection::PORTS, $port]);
            }
        }

        return $table;
    }
}
