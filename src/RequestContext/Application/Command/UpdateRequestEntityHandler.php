<?php

namespace App\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\UpdateRequestEntity;
use App\RequestContext\Domain\Event\RequestEntityNameUpdated;
use App\RequestContext\Domain\Event\RequestEntityStatusUpdated;
use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UpdateRequestEntityHandler
{
    public function __construct(
        private readonly RequestEntityRepository  $requestEntityRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function handle(UpdateRequestEntity $command): void
    {
        $requestEntity = $this->requestEntityRepository->findById($command->id());

        if ($requestEntity === null) {
            throw new RequestEntityNotFoundException($command->id());
        }

        if ($command->name() !== null && $requestEntity->name() !== $command->name()) {
            $this->eventDispatcher->dispatch(new RequestEntityNameUpdated($requestEntity->id()));
        }

        if ($command->requestStatus() !== null && !$requestEntity->requestStatus()->equals($command->requestStatus())) {
            $this->eventDispatcher->dispatch(new RequestEntityStatusUpdated($requestEntity->id()));
        }

        $requestEntity->update($command);

        $this->requestEntityRepository->save($requestEntity);
    }
}
