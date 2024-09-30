<?php

namespace App\RequestContext\Domain\Event;

class RequestEntityNameUpdated
{
    private string $id;

    public function __construct(
        string $id
    )
    {
        $this->id = $id;
    }

    public function id(): string
    {
        return $this->id;
    }
}