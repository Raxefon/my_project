<?php

namespace App\RequestContext\Domain\ModelView;

use App\RequestContext\Domain\Model\RequestEntity;

class RequestEntityFactory
{
    public static function create(
        RequestEntity $requestEntity
    )
    {
        return new RequestEntityView(
            $requestEntity->id(),
            $requestEntity->name(),
        );
    }
}