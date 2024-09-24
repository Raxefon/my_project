<?php

namespace App\RequestContext\Application\Query;

class FindRequestEntityById
{
    public function __construct(
        private readonly int $id
    )
    {
    }

    public function id(): int
    {
        return $this->id;
    }
}