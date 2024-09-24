<?php

namespace App\RequestContext\Application\Query;

use App\RequestContext\Domain\Model\RequestEntityRepository;
use App\RequestContext\Domain\ModelView\RequestEntityFactory;

class FindAllRequestEntityByIdHandler
{
    public function __construct(
        private readonly RequestEntityRepository $RequestEntityRepository
    )
    {
    }

    public function __invoke(FindAllRequestEntities $query): array
    {
        $requestEntities = $this->RequestEntityRepository->findAll();

        $collection = [];
        foreach ($requestEntities as $requestEntity) {
            $collection[] = RequestEntityFactory::create($requestEntity);
        }

        return $collection;
    }
}