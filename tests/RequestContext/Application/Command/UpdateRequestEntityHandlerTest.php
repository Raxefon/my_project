<?php

namespace App\Tests\RequestContext\Application\Command;

use App\RequestContext\Application\Command\UpdateRequestEntityHandler;
use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Command\UpdateRequestEntity;
use App\RequestContext\Domain\Event\RequestEntityNameUpdated;
use App\RequestContext\Domain\Event\RequestEntityStatusUpdated;
use App\RequestContext\Domain\Exception\RequestEntityNotFoundException;
use App\RequestContext\Domain\Model\RequestEntity;
use App\RequestContext\Domain\ValueObject\RequestStatus;
use App\Tests\RequestContext\RequestUnitTestCase;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
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

    public function test_should_update_name_request_entity(): void
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
            ->with($this->isInstanceOf(RequestEntityNameUpdated::class));

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

    public function test_should_update_status_accepted_request_entity(): void
    {
        $employeeId = Uuid::uuid4()->toString();

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
            null,
            RequestStatus::accepted(),
            $employeeId
        );

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RequestEntityStatusUpdated::class));

        $this->updateRequestEntityHandler->handle($updateRequestEntity);

        /** @var UpdateRequestEntity $updatedEntity */
        $updatedEntity = $this->requestEntityRepository()->findById($requestEntity->id());

        $this->assertEquals(true, $updatedEntity->requestStatus()->isAccepted());
    }

    public function test_should_update_status_rejected_request_entity(): void
    {
        $employeeId = Uuid::uuid4()->toString();

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
            null,
            RequestStatus::rejected(),
            $employeeId
        );

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(RequestEntityStatusUpdated::class));

        $this->updateRequestEntityHandler->handle($updateRequestEntity);

        /** @var RequestEntity $updatedEntity */
        $updatedEntity = $this->requestEntityRepository()->findById($requestEntity->id());

        $this->assertTrue($updatedEntity->requestStatus()->isRejected());
    }

    public function test_should_update_status_pending_request_entity(): void
    {
        $employeeId = Uuid::uuid4()->toString();

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
            null,
            RequestStatus::pending(),
            $employeeId
        );

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $this->updateRequestEntityHandler->handle($updateRequestEntity);

        /** @var RequestEntity $updatedEntity */
        $updatedEntity = $this->requestEntityRepository()->findById($requestEntity->id());

        $this->assertTrue($updatedEntity->requestStatus()->isPending());
    }

    public function test_should_not_update_request_entity_with_invalid_status(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $employeeId = Uuid::uuid4()->toString();

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
            null,
            RequestStatus::from('invalid_status'),
            $employeeId
        );

        $this->updateRequestEntityHandler->handle($updateRequestEntity);
    }

    public function test_should_not_dispatch_event_if_status_does_not_change(): void
    {
        $employeeId = Uuid::uuid4()->toString();

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
            null,
            $requestEntity->requestStatus(),
            $employeeId
        );

        $this->eventDispatcher
            ->expects($this->never())
            ->method('dispatch');

        $this->updateRequestEntityHandler->handle($updateRequestEntity);
    }
}
