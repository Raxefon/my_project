<?php

namespace App\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Event\RequestEntityCreated;
use App\RequestContext\Domain\Exception\InvalidRequestEntityNameException;
use App\RequestContext\Domain\Exception\RequestEntityAlreadyExistsException;
use App\RequestContext\Domain\Model\RequestEntity;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CreateRequestEntityHandler
{
    public function __construct(
        private readonly RequestEntityRepository  $requestEntityRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function handle(CreateRequestEntity $command): void
    {
        if ($command->name() === null || $command->name() === '') {
            throw new InvalidRequestEntityNameException();
        }

        $existingEntity = $this->requestEntityRepository->findByName($command->name());
        if ($existingEntity !== null) {
            throw new RequestEntityAlreadyExistsException("A request entity with the name '{$command->name()}' already exists.");
        }

        $requestEntity = new RequestEntity(
            Uuid::uuid4()->toString(),
            $command->name()
        );

        $this->eventDispatcher->dispatch(new RequestEntityCreated($requestEntity->id()));

        $this->requestEntityRepository->save($requestEntity);
    }
}
