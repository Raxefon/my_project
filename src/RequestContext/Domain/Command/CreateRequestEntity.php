<?php

namespace App\RequestContext\Domain\Command;

use App\RequestContext\Domain\Permission\RequestEntityEntitlementPermissable;

class CreateRequestEntity implements RequestEntityEntitlementPermissable
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }
}