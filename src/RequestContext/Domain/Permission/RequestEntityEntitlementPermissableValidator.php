<?php

namespace App\RequestContext\Domain\Permission;

use App\Shared\Domain\Auth\AuthSessionServiceInterface;

class RequestEntityEntitlementPermissableValidator
{
    private AuthSessionServiceInterface $authSessionServiceInterface;

    public function __construct(
        AuthSessionServiceInterface $authSessionServiceInterface
    )
    {
        $this->authSessionServiceInterface = $authSessionServiceInterface;
    }

    public function validate(RequestEntityEntitlementPermissable $permissable): void
    {
        $entityReference = $this->authSessionServiceInterface->entityReference();

        if (!$entityReference) {
            throw new \DomainException('Entity reference not found');
        }

        /*Logica que comprueba si el usuario existe y tiene permisos*/
    }
}