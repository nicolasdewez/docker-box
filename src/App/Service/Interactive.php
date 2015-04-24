<?php

namespace App\Service;

use Symfony\Component\Console\Question\ChoiceQuestion;

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
        array_unshift($containers, 'Quit');
        $question = new ChoiceQuestion(
            '<question>Please enter the index of container to delete :</question> ',
            $containers,
            0
        );
        $question->setErrorMessage('Your choice is not valid');

        return $question;
    }
}
