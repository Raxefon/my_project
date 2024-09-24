<?php

namespace App\Tests\RequestContext\Domain\Model;

use App\RequestContext\Domain\Model\RequestEntity;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use App\Tests\ddd\Domain\Model\InMemoryRepository;

class InMemoryRequestEntityRepository extends InMemoryRepository implements RequestEntityRepository
{

    public function findAll(): array
    {
        return $this->allEntities();
    }

    public function findById(string $requestEntityId): ?RequestEntity
    {
        return $this->find($requestEntityId);
    }

    public function save(RequestEntity $requestEntity): void
    {
        $this->persist($requestEntity);
    }

    public function delete(RequestEntity $requestEntity): void
    {
        $this->unset($requestEntity);
    }

    public function findByName(string $name): ?RequestEntity
    {
        foreach ($this->allEntities() as $entity) {
            if ($entity->name() === $name) {
                return $entity;
            }
        }

        return null;
    }
}