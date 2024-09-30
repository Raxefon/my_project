<?php

namespace App\RequestContext\Domain\Model;

use App\RequestContext\Domain\Command\UpdateRequestEntity;
use App\RequestContext\Domain\ValueObject\RequestStatus;
use Carbon\CarbonImmutable;

class RequestEntity
{
    private string $id;
    private string $name;
    private CarbonImmutable $createdAt;
    private CarbonImmutable $updatedAt;
    private ?CarbonImmutable $deletedAt;
    private ?CarbonImmutable $resolvedAt;
    private ?string $resolvedBy;
    private RequestStatus $status;

    public function __construct(
        string $id,
        string $name
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->status = RequestStatus::pending();
        $this->createdAt = CarbonImmutable::now()->utc();
        $this->updatedAt = CarbonImmutable::now()->utc();
        $this->deletedAt = null;
        $this->resolvedAt = null;
        $this->resolvedBy = null;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CarbonImmutable
    {
        return $this->deletedAt;
    }

    public function update(UpdateRequestEntity $command): void
    {
        $this->name = $command->name() !== null ? $command->name() : $this->name;
        $this->status = $command->requestStatus() !== null ? $command->requestStatus() : $this->status;

        if ($this->status->isAccepted() || $this->status->isRejected()) {

            $this->resolvedAt = CarbonImmutable::now()->utc();
            $this->resolvedBy = $command->employeeId();
        }

        $this->updatedAt = CarbonImmutable::now()->utc();
    }

    public function delete(): void
    {
        $this->deletedAt = CarbonImmutable::now()->utc();
    }

    public function resolvedAt(): ?CarbonImmutable
    {
        return $this->resolvedAt;
    }

    public function resolvedBy(): ?string
    {
        return $this->resolvedBy;
    }

    public function requestStatus(): RequestStatus
    {
        return $this->status;
    }
}
