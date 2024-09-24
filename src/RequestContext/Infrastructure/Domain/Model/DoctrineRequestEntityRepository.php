<?php

namespace App\RequestContext\Infrastructure\Domain\Model;

use App\RequestContext\Domain\Model\RequestEntity;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineRequestEntityRepository implements RequestEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(RequestEntity::class)->findAll();
    }

    public function findById(string $requestEntityId): ?RequestEntity
    {
        return $this->entityManager->getRepository(RequestEntity::class)->find($requestEntityId);
    }

    public function save(RequestEntity $requestEntity): void
    {
        $this->entityManager->persist($requestEntity);
        $this->entityManager->flush();
    }

    public function delete(RequestEntity $requestEntity): void
    {
        $this->entityManager->remove($requestEntity);
        $this->entityManager->flush();
    }

    public function findByName(string $name): ?RequestEntity
    {
        $conn = $this->entityManager->getConnection();

        $sql = 'SELECT * FROM request_entities WHERE name = :name LIMIT 1';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['name' => $name])->fetchAssociative();

        if ($result === false) {
            return null;
        }

        return new RequestEntity(
            $result['id'],
            $result['name']
        );
    }
}