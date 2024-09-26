<?php

namespace App\Shared\Domain\Auth;

use App\Shared\Domain\ValueObject\EntityReference;

interface AuthSessionServiceInterface
{
    public function entityReference(): ?EntityReference;
}