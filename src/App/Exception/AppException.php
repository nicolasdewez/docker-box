<?php

namespace App\Exception;

/**
 * Class AppException.
 */
class AppException extends \Exception
{
    /** @var string */
    protected $command;

    /**
     * @param string $message
     * @param string $command
     * @param int    $code
     */
    public function __construct($message, $command = '', $code = 0)
    {
        $this->command = $command;
        parent::__construct($message, $code);
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }
}
