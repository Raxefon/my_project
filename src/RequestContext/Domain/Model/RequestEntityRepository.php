<?php

namespace App\RequestContext\Domain\Model;

interface RequestEntityRepository
{
    public function findAll(): array;

    public function findById(string $requestEntityId): ?RequestEntity;

    public function save(RequestEntity $requestEntity): void;

    public function delete(RequestEntity $requestEntity): void;
}