<?php

namespace App\RequestContext\Infrastructure\Controller\PrivateApi\v1;

use App\RequestContext\Application\Query\FindAllRequestEntities;
use App\RequestContext\Application\Query\FindRequestEntityById;
use App\RequestContext\Domain\Command\CreateRequestEntity;
use App\RequestContext\Domain\Command\DeleteRequestEntity;
use App\RequestContext\Domain\Command\UpdateRequestEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class RequestEntityController extends AbstractController
{
    private MessageBusInterface $queryBus;
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $queryBus, MessageBusInterface $commandBus)
    {
        $this->queryBus = $queryBus;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/request_entities", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $query = new FindAllRequestEntities();
        $entities = $this->queryBus->dispatch($query);

        return $this->json($entities);
    }

    /**
     * @Route("/request_entity/{id}", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        $query = new FindRequestEntityById($id);
        $entity = $this->queryBus->dispatch($query);

        return $this->json($entity);
    }

    /**
     * @Route("/request_entity", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new CreateRequestEntity($data['name']);
        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'Entity created'], 201);
    }

    /**
     * @Route("/request_entity/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $command = new UpdateRequestEntity($id, $data['name']);
        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'Entity updated']);
    }

    /**
     * @Route("/request_entity/{id}", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $command = new DeleteRequestEntity($id);
        $this->commandBus->dispatch($command);

        return $this->json(['message' => 'Entity deleted'], 204);
    }
}