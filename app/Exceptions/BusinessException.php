<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends \RuntimeException
{
    private $userMessage;

    public function __construct($userMessage)
    {
        $this->userMessage = $userMessage;

        parent::__construct($userMessage);
    }

    public function getUserMessage()
    {
        return $this->userMessage;
    }
}
