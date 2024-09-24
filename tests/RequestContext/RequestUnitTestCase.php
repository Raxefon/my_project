<?php

namespace App\Tests\RequestContext;

use App\RequestContext\Application\Command\CreateRequestEntityHandler;
use App\RequestContext\Application\Command\DeleteRequestEntityHandler;
use App\RequestContext\Application\Command\UpdateRequestEntityHandler;
use App\Tests\RequestContext\Domain\Model\InMemoryRequestEntityRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class RequestUnitTestCase extends TestCase
{
    protected function requestEntityRepository(): InMemoryRequestEntityRepository
    {
        if (!isset($this->requestEntityRepository)) {
            $this->requestEntityRepository = new InMemoryRequestEntityRepository();
        }

        return $this->requestEntityRepository;
    }

    protected function eventDispatcher(): EventDispatcherInterface
    {
        if (!isset($this->eventDispatcher)) {
            $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        }

        return $this->eventDispatcher;
    }

    protected function createRequestEntityHandler(): CreateRequestEntityHandler
    {
        if (!isset($this->createRequestEntityHandler)) {
            $this->createRequestEntityHandler = new CreateRequestEntityHandler(
                $this->requestEntityRepository(),
                $this->eventDispatcher()
            );
        }

        return $this->createRequestEntityHandler;
    }

    protected function deleteRequestEntityHandler(): DeleteRequestEntityHandler
    {
        if (!isset($this->deleteRequestEntityHandler)) {
            $this->deleteRequestEntityHandler = new DeleteRequestEntityHandler(
                $this->requestEntityRepository(),
                $this->eventDispatcher()
            );
        }

        return $this->deleteRequestEntityHandler;
    }

    protected function updateRequestEntityHandler(): UpdateRequestEntityHandler
    {
        if (!isset($this->updateRequestEntityHandler)) {
            $this->updateRequestEntityHandler = new UpdateRequestEntityHandler(
                $this->requestEntityRepository(),
                $this->eventDispatcher()
            );
        }

        return $this->updateRequestEntityHandler;
    }
}