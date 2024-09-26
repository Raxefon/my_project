<?php

namespace App\RequestContext\Domain\Command;

use App\RequestContext\Domain\Permission\RequestEntityEntitlementPermissable;

class DeleteRequestEntity implements RequestEntityEntitlementPermissable
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