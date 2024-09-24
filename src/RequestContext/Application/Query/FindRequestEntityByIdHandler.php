<?php

namespace App\RequestContext\Application\Query;

use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntityRepository;
use App\RequestContext\Domain\ModelView\RequestEntityFactory;
use App\RequestContext\Domain\ModelView\RequestEntityView;

class FindRequestEntityByIdHandler
{
    public function __construct(
        private readonly RequestEntityRepository $RequestEntityRepository
    )
    {
    }

    public function __invoke(FindRequestEntityById $query): ?RequestEntityView
    {
        $RequestEntity = $this->RequestEntityRepository->findById($query->id());

        if ($RequestEntity === null) {
            throw new RequestEntityNotFoundException();
        }

        return RequestEntityFactory::create($RequestEntity);
    }
}