<?php

declare(strict_types=1);

namespace App\Tests\ddd\Domain\Model;

abstract class InMemoryRepository
{
    public array $entities = [];

    protected function persist($entity): void
    {
        $index = null;
        foreach ($this->entities as $nodeIndex => $nodeEntity) {
            if ($nodeEntity->id() === $entity->id()) {
                $index = $nodeIndex;
                break;
            }
        }

        if (is_null($index)) {
            $this->entities[] = $entity;
        } else {
            $this->entities[$index] = $entity;
        }
    }

    protected function removeFromEntity($entity): void
    {
        foreach ($this->entities as $nodeIndex => $nodeEntity) {
            if ($nodeEntity->id() === $entity->id()) {
                unset($this->entities[$nodeIndex]);
                return;
            }
        }
    }

    protected function find($entityId): ?object
    {
        foreach ($this->entities as $entity) {
            if ($entity->id() === $entityId) {
                return $entity;
            }
        }

        return null;
    }

    protected function findBy(string $value, string $key): array
    {
        return array_map(
            static function ($entity) {
                return $entity;
            },
            array_values(
                array_filter(
                    $this->entities,
                    static function ($entity) use ($value, $key) {
                        return $entity->$key() === $value;
                    }
                )
            )
        );
    }

    protected function findOneBy(string $value, string $key): ?object
    {
        $results = $this->findBy($value, $key);
        return empty($results) ? null : $results[0];
    }


    protected function unset($entity): void
    {
        foreach ($this->entities as $nodeIndex => $nodeEntity) {
            if ($entity->id() === $nodeEntity->id()) {
                unset($this->entities[$nodeIndex]);
            }
        }
        $this->entities = array_values($this->entities);
    }

    public function unsetEntities($entities): void
    {
        foreach ($entities as $entity) {
            $this->unset($entity);
        }
    }

    public function first(): ?object
    {
        return empty($this->entities) ? null : $this->entities[0];
    }

    public function last(): ?object
    {
        return empty($this->entities) ? null : end($this->entities);
    }

    public function allEntities(): array
    {
        return $this->entities;
    }

    public function allNotDeleted(): array
    {
        return array_values(
            array_filter(
                $this->entities,
                static function ($entity) {
                    return $entity->deletedAt() === null;
                }
            )
        );
    }
}
