<?php

namespace App\Tests\RequestContext\Application\Command;

use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Exception\InvalidRequestEntityNameException;
use App\RequestContext\Domain\Exception\RequestEntityAlreadyExistsException;
use App\RequestContext\Domain\Model\RequestEntity;
use App\Tests\RequestContext\RequestUnitTestCase;
use Carbon\CarbonImmutable;

/**
 * @covers \App\RequestContext\Application\Command\CreateRequestEntityHandler
 */
class CreateRequestEntityHandlerTest extends RequestUnitTestCase
{
    protected function setUp(): void
    {
        $this->createRequestEntityHandler = $this->createRequestEntityHandler();
    }

    public function test_should_create_request_entity(): void
    {
        $createRequestEntity = new CreateRequestEntity(
            'First request'
        );

        $this->createRequestEntityHandler->handle($createRequestEntity);

        /** @var RequestEntity $requestEntityFirst */
        $requestEntityFirst = $this->requestEntityRepository()->first();

        $this->assertCount(1, $this->requestEntityRepository()->allEntities());
        $this->assertEquals($requestEntityFirst->name(), $createRequestEntity->name());

        $this->assertNotNull($requestEntityFirst->createdAt());
        $this->assertNotNull($requestEntityFirst->updatedAt());

        $now = CarbonImmutable::now()->utc();
        $this->assertTrue($requestEntityFirst->createdAt()->diffInSeconds($now) < 1);
        $this->assertTrue($requestEntityFirst->updatedAt()->diffInSeconds($now) < 1);

        $this->assertNull($requestEntityFirst->deletedAt());
    }

    public function test_should_throw_exception_when_name_is_empty(): void
    {
        $this->expectException(InvalidRequestEntityNameException::class);

        $createRequestEntity = new CreateRequestEntity('');
        $this->createRequestEntityHandler->handle($createRequestEntity);
    }

    public function test_should_throw_exception_when_entity_with_same_name_exists(): void
    {
        $createRequestEntity = new CreateRequestEntity('Duplicate name');
        $this->createRequestEntityHandler->handle($createRequestEntity);

        $this->expectException(RequestEntityAlreadyExistsException::class);
        $createRequestEntityDuplicate = new CreateRequestEntity('Duplicate name');
        $this->createRequestEntityHandler->handle($createRequestEntityDuplicate);
    }
}
