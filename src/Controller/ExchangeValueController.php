<?php

namespace App\Controller;

use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ExchangeValueController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route("/exchange/values", methods: ["POST"])]
    public function exchangeValues(Request $request): JsonResponse
    {
        // Get data from the request
        $data = json_decode($request->getContent(), true);

        // Create a new history object
        $history = new History();
        $history->setFirstIn($data['first']);
        $history->setSecondIn($data['second']);
        $history->setCreatedAt(new \DateTimeImmutable());

        // Swap values
        $history->setFirstOut($history->getSecondIn());
        $history->setSecondOut($history->getFirstIn());

        // Update the date
        $history->setUpdateAt(new \DateTimeImmutable());

        // Save to the database
        $this->entityManager->persist($history);
        $this->entityManager->flush();

        // Return JSON response
        $response = [
            'firstOut' => $history->getFirstOut(),
            'secondOut' => $history->getSecondOut(),
            'updateAt' => $history->getUpdateAt()->format('Y-m-d H:i:s'),
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route("/exchange/history", methods: ["GET", "POST"])]
    public function getAllHistoryRecords(Request $request): JsonResponse
    {
        // Pagination
        $page = $request->query->getInt('page', '1');
        $perPage = $request->query->getInt('perPage', '10');

        // Sorting
        $sortColumn = $request->query->get('sortColumn', 'updateAt');
        $sortOrder = $request->query->get('sortOrder', 'desc');

        // Sorting validation
        if (!in_array($sortColumn, ['createdAt', 'updateAt', 'firstOut', 'secondOut', 'firstIn', 'secondIn'])) {
            return new JsonResponse(['error' => 'Invalid sort column.'], Response::HTTP_BAD_REQUEST);
        }

        if (!in_array($sortOrder, ['asc', 'desc'])) {
            return new JsonResponse(['error' => 'Invalid sort order.'], Response::HTTP_BAD_REQUEST);
        }

        // Fetch data from the database with pagination and sorting
        $historyRecords = $this->entityManager->getRepository(History::class)
            ->findBy([], [$sortColumn => $sortOrder], $perPage, ($page - 1) * $perPage);

        // Prepare data for the response
        $response = [];

        foreach ($historyRecords as $record) {
            $response[] = [
                'firstIn' => $record->getFirstIn(),
                'secondIn' => $record->getSecondIn(),
                'firstOut' => $record->getFirstOut(),
                'secondOut' => $record->getSecondOut(),
                'createdAt' => $record->getCreatedAt()->format('Y-m-d H:i:s'),
                'updateAt' => $record->getUpdateAt()->format('Y-m-d H:i:s'),
            ];
        }

        // Return JSON response with paginated and sorted history records
        return new JsonResponse($response, Response::HTTP_OK);
    }
}
