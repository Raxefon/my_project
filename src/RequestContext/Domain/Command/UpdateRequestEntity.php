<?php

namespace App\RequestContext\Domain\Command;

use App\RequestContext\Domain\Permission\RequestEntityEntitlementPermissable;

class UpdateRequestEntity implements RequestEntityEntitlementPermissable
{
    private string $id;
    private string $name;

    public function __construct(
        string $id,
        string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}