<?php

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

    public function delete(RequestEntity $RequestEntity): void
    {
        $this->entityManager->remove($RequestEntity);
        $this->entityManager->flush();
    }
}