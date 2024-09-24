<?php

namespace App\RequestContext\Domain\Command;

class DeleteRequestEntity
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