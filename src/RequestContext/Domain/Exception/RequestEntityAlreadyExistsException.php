<?php

namespace App\RequestContext\Domain\Exception;

class RequestEntityAlreadyExistsException extends \Exception
{
    public function __construct(string $message = "")
    {
        parent::__construct($message);
    }
}