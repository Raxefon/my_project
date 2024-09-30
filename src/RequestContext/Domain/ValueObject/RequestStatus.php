<?php

declare(strict_types=1);

namespace App\RequestContext\Domain\ValueObject;

use InvalidArgumentException;

final class RequestStatus
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';

    private string $status;

    private const ALLOWED_STATUSES = [
        self::PENDING,
        self::ACCEPTED,
        self::REJECTED,
    ];

    private function __construct(string $status)
    {
        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new InvalidArgumentException($status);
        }

        $this->status = $status;
    }

    public static function from(string $status): self
    {
        return new self($status);
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function accepted(): self
    {
        return new self(self::ACCEPTED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::REJECTED;
    }

    public function equals(self $other): bool
    {
        return $this->status === $other->status;
    }
}
