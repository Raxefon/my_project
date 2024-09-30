<?php

namespace App\RequestContext\Domain\Command;

use App\RequestContext\Domain\Permission\RequestEntityEntitlementPermissable;
use App\RequestContext\Domain\ValueObject\RequestStatus;

class UpdateRequestEntity implements RequestEntityEntitlementPermissable
{
    private string $id;
    private ?string $name;
    private ?RequestStatus $requestStatus;
    private ?string $employeeId;

    public function __construct(
        string $id,
        string $name = null,
        ?RequestStatus $requestStatus = null,
        ?string $employeeId = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->requestStatus = $requestStatus;
        $this->employeeId = $employeeId;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function requestStatus(): ?RequestStatus
    {
        return $this->requestStatus;
    }

    public function employeeId(): ?string
    {
        return $this->employeeId;
    }
}