<?php

namespace App\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Model\RequestEntity;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use Ramsey\Uuid\Uuid;

class CreateRequestEntityHandler
{
    public function __construct(
        private readonly RequestEntityRepository $requestEntityRepository)
    {
    }

    public function handle(CreateRequestEntity $command): void
    {
        $requestEntity = new RequestEntity(
            Uuid::uuid4()->toString(),
            $command->name()
        );
        $this->requestEntityRepository->save($requestEntity);
    }
}
