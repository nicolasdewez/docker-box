<?php

namespace App\Service;

use App\Exception\BadResponseException;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Class Interactive.
 */
class Interactive
{
    const QUIT = 'Quit';
    const YES = 'Yes';
    const NO = 'No';

    /**
     * @param array $containers
     *
     * @return ChoiceQuestion
     */
    public function commandListQuestionDelete(array $containers)
    {
        array_unshift($containers, 'Quit');
        $question = new ChoiceQuestion(
            '<question>Please enter the index of container to delete :</question> ',
            $containers,
            0
        );
        $question->setErrorMessage('Your choice is not valid');

        return $question;
    }

    /**
     * @return ChoiceQuestion
     */
    public function commandAddQuestionMultiple()
    {
        $answers = [self::NO, self::YES];
        $question = new ChoiceQuestion(
            '<question>This container can be launch several times ?</question> ',
            $answers,
            0
        );
        $question->setValidator(function ($answer) use ($answers) {
            if (!is_numeric($answer) || $answer < 0 || $answer >= count($answers)) {
                throw new BadResponseException('Bad value');
            }
            if (0 === $answer) {
                return false;
            }

            return true;
        });
        $question->setMaxAttempts(2);

        return $question;
    }

    /**
     * @return Question
     */
    public function commandAddQuestionCommand()
    {
        $question = new Question('<question>Please enter the command to launch container (ex: docker run ...) :</question> ');
        $question->setValidator(function ($answer) {
            if (empty($answer)) {
                throw new BadResponseException('Value is required');
            }

            return $answer;
        });
        $question->setMaxAttempts(2);

        return $question;
    }
}
