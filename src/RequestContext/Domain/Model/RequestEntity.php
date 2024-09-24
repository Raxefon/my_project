<?php

namespace App\RequestContext\Domain\Model;

use App\RequestContext\Domain\Command\UpdateRequestEntity;
use Carbon\CarbonImmutable;

class RequestEntity
{
    private string $id;
    private string $name;
    private CarbonImmutable $createdAt;
    private CarbonImmutable $updatedAt;
    private ?CarbonImmutable $deletedAt;

    public function __construct(
        string $id,
        string $name
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->createdAt = CarbonImmutable::now()->utc();
        $this->updatedAt = CarbonImmutable::now()->utc();
        $this->deletedAt = null;
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
        $this->name = $command->name();
        $this->updatedAt = CarbonImmutable::now()->utc();
    }

    public function delete(): void
    {
        $this->deletedAt = CarbonImmutable::now()->utc();
    }
}
