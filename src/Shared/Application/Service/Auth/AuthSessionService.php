<?php

namespace App\Shared\Application\Service\Auth;

use App\Shared\Domain\Auth\AuthSessionServiceInterface;
use App\Shared\Domain\ValueObject\EntityReference;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthSessionService implements AuthSessionServiceInterface
{

    private SessionInterface $session;

    public function __construct(SessionInterface $session)
    {

        $this->session = $session;
    }

    public function entityReference(): ?EntityReference
    {
        $userId = $this->session->get('user_id');

        if (!$userId) {
            return null;
        }

        return new EntityReference($userId);
    }
}