<?php

namespace App\Tests\RequestContext;

use App\RequestContext\Application\Command\CreateRequestEntityHandler;
use App\Tests\RequestContext\Domain\Model\InMemoryRequestRepository;
use PHPUnit\Framework\TestCase;

abstract class RequestUnitTestCase extends TestCase
{
    protected function requestRepository(): InMemoryRequestRepository
    {
        if (!isset($this->requestRepository)) {
            $this->requestRepository = new InMemoryRequestRepository();
        }

        return $this->requestRepository;
    }

    protected function createRequestEntityHandler(): CreateRequestEntityHandler
    {
        if (!isset($this->createRequestEntityHandler)) {
            $this->createRequestEntityHandler = new CreateRequestEntityHandler(
                $this->requestRepository()
            );
        }

        return $this->createRequestEntityHandler;
    }
}