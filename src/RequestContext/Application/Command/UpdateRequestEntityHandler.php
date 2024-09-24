<?php

namespace App\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\UpdateRequestEntity;
use App\RequestContext\Domain\Event\RequestEntityUpdated;
use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UpdateRequestEntityHandler
{
    public function __construct(
        private readonly RequestEntityRepository $requestEntityRepository,
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

        $requestEntity->update($command);

        //Debería de estar en el modelo y no en la logica de negocio
        $this->eventDispatcher->dispatch(new RequestEntityUpdated($requestEntity->id()));

        $this->requestEntityRepository->save($requestEntity);
    }
}
