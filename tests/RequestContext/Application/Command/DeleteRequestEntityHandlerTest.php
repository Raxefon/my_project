<?php

namespace App\Tests\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Command\DeleteRequestEntity;
use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntity;
use App\Tests\RequestContext\RequestUnitTestCase;
use Carbon\CarbonImmutable;

/**
 * @covers \App\RequestContext\Application\Command\DeleteRequestEntityHandler
 */
class DeleteRequestEntityHandlerTest extends RequestUnitTestCase
{
    protected function setUp(): void
    {
        $this->createRequestEntityHandler = $this->createRequestEntityHandler();
        $this->deleteRequestEntityHandler = $this->deleteRequestEntityHandler();
    }

    public function test_should_create_request_entity(): void
    {
        $createRequestEntity = new CreateRequestEntity(
            'First request'
        );

        $this->createRequestEntityHandler()->handle($createRequestEntity);

        /** @var RequestEntity $requestEntity */
        $requestEntity = $this->requestEntityRepository()->last();

        $this->assertNull($requestEntity->deletedAt());

        $deleteRequestEntity = new DeleteRequestEntity(
            $requestEntity->id()
        );

        $this->deleteRequestEntityHandler->handle($deleteRequestEntity);

        $this->assertNotNull($requestEntity->deletedAt());
        $now = CarbonImmutable::now()->utc();
        $this->assertTrue($requestEntity->deletedAt()->diffInSeconds($now) < 1);
    }

    public function test_should_throw_exception_if_entity_not_found(): void
    {
        $this->expectException(RequestEntityNotFoundException::class);

        $deleteRequestEntity = new DeleteRequestEntity('non-existent-id');
        $this->deleteRequestEntityHandler->handle($deleteRequestEntity);
    }

    public function test_should_handle_deleting_already_deleted_entity(): void
    {
        $createRequestEntity = new CreateRequestEntity('First request');
        $this->createRequestEntityHandler()->handle($createRequestEntity);

        /** @var RequestEntity $requestEntity */
        $requestEntity = $this->requestEntityRepository()->last();

        $deleteRequestEntity = new DeleteRequestEntity($requestEntity->id());
        $this->deleteRequestEntityHandler->handle($deleteRequestEntity);

        $this->assertNotNull($requestEntity->deletedAt());

        $this->deleteRequestEntityHandler->handle($deleteRequestEntity);

        $this->assertEquals($requestEntity->deletedAt(), $requestEntity->deletedAt());
    }
}
