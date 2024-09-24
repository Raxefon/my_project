<?php

namespace App\Tests\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Model\RequestEntity;
use App\Tests\RequestContext\RequestUnitTestCase;

class CreateRequestEntityHandlerTest extends RequestUnitTestCase
{
    protected function setUp(): void
    {
        $this->createRequestEntityHandler = $this->createRequestEntityHandler();
    }

    public function test_should_create_request_entity(): void
    {
        $createRequestEntity = new CreateRequestEntity(
            'Primer request'
        );

        $this->createRequestEntityHandler()->handle($createRequestEntity);

        /** @var RequestEntity $requestEntityFirst */
        $requestEntityFirst = $this->requestRepository()->first();

        $this->assertCount(1, $this->requestRepository()->allEntities());
        $this->assertEquals($requestEntityFirst->name(), $createRequestEntity->name());
    }
}
