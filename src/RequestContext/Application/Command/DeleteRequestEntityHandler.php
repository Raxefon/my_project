<?php

namespace App\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\DeleteRequestEntity;
use App\RequestContext\Domain\Event\RequestEntityDeleted;
use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DeleteRequestEntityHandler
{
    public function __construct(
        private readonly RequestEntityRepository $requestEntityRepository,
        private readonly EventDispatcherInterface $eventDispatcher
    )
    {
    }

    public function handle(DeleteRequestEntity $command): void
    {
        $requestEntity = $this->requestEntityRepository->findById($command->id());

        if ($requestEntity === null) {
            throw new RequestEntityNotFoundException($command->id());
        }

        $requestEntity->delete();

        $this->eventDispatcher->dispatch(new RequestEntityDeleted($requestEntity->id()));

        $this->requestEntityRepository->save($requestEntity);
    }
}
