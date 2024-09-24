<?php

namespace App\Tests\RequestContext\Application\Command;

use App\RequestContext\Application\Command\UpdateRequestEntityHandler;
use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Command\UpdateRequestEntity;
use App\RequestContext\Domain\Event\RequestEntityUpdated;
use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntity;
use App\Tests\RequestContext\RequestUnitTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @covers \App\RequestContext\Application\Command\UpdateRequestEntityHandler
 */
class UpdateRequestEntityHandlerTest extends RequestUnitTestCase
{
    protected function setUp(): void
    {
        $this->createRequestEntityHandler = $this->createRequestEntityHandler();
        $this->updateRequestEntityHandler = $this->updateRequestEntityHandler();
    }

    public function test_should_update_request_entity(): void
    {
        $createRequestEntity = new CreateRequestEntity(
            'First request'
        );

        $this->createRequestEntityHandler()->handle($createRequestEntity);

        /** @var RequestEntity $requestEntity */
        $requestEntity = $this->requestEntityRepository()->last();

        $this->assertNotNull($requestEntity);
        $this->assertEquals('First request', $requestEntity->name());
        $this->assertNull($requestEntity->deletedAt());

        $updateRequestEntity = new UpdateRequestEntity(
            $requestEntity->id(),
            'Updated name request'
        );

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RequestEntityUpdated::class));

        $this->updateRequestEntityHandler->handle($updateRequestEntity);

        $updatedEntity = $this->requestEntityRepository()->findById($requestEntity->id());
        $this->assertEquals('Updated name request', $updatedEntity->name());
    }

    public function test_should_throw_exception_when_entity_not_found(): void
    {
        $updateRequestEntity = new UpdateRequestEntity(
            'non-existent-id',
            'Updated name request'
        );

        $this->expectException(RequestEntityNotFoundException::class);
        $this->expectExceptionMessage('non-existent-id');

        $this->updateRequestEntityHandler()->handle($updateRequestEntity);
    }
}
