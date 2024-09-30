<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Service\GuestService;
use App\Helper\ResponseHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Annotation\Groups;

#[Route('/guest')]
final class GuestController extends AbstractController
{
    private GuestService $guestService;

    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }

    #[Route(name: 'app_guest_index', methods: ['GET'])]
    #[OA\Get(
        path: '/guest',
        summary: 'Получить всех гостей',
        tags: ['Guests'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Возвращает список гостей',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: new Model(type: Guest::class, groups: ['guest:read'])))
            )
        ]
    )]
    public function index(): Response
    {
        $startTime = microtime(true);

        $guests = $this->guestService->getAllGuestsAsArray();

        $response = new JsonResponse($guests, Response::HTTP_OK);
        return ResponseHelper::addDebugHeaders($response, $startTime);
    }

    #[Route('/new', name: 'app_guest_new', methods: ['POST'])]
    #[OA\Post(
        path: '/guest/new',
        summary: 'Создать нового гостя',
        tags: ['Guests'],
        requestBody: new OA\RequestBody(
            description: 'Данные для создания гостя',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: Guest::class, groups: ['guest:write']))
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Гость успешно создан',
                content: new OA\JsonContent(ref: new Model(type: Guest::class, groups: ['guest:read']))
            )
        ]
    )]
    public function new(Request $request): Response
    {
        $startTime = microtime(true);

        $data = json_decode($request->getContent(), true);
        $guest = $this->guestService->createGuest($data);

        $response = new JsonResponse([
            'id' => $guest->getId(),
            'message' => 'Guest created successfully'
        ], Response::HTTP_CREATED);

        return ResponseHelper::addDebugHeaders($response, $startTime);
    }

    #[Route('/{id}', name: 'app_guest_show', methods: ['GET'])]
    #[OA\Get(
        path: '/guest/{id}',
        summary: 'Получить информацию о госте',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', description: 'ID гостя', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Информация о госте',
                content: new OA\JsonContent(ref: new Model(type: Guest::class, groups: ['guest:read']))
            )
        ]
    )]
    public function show(int $id): Response
    {
        $startTime = microtime(true);

        $guest = $this->guestService->getGuestByIdAsArray($id);

        $response = new JsonResponse($guest, Response::HTTP_OK);
        return ResponseHelper::addDebugHeaders($response, $startTime);
    }

    #[Route('/{id}/edit', name: 'app_guest_edit', methods: ['PUT'])]
    #[OA\Put(
        path: '/guest/{id}/edit',
        summary: 'Обновить информацию о госте',
        tags: ['Guests'],
        requestBody: new OA\RequestBody(
            description: 'Данные для обновления гостя',
            required: true,
            content: new OA\JsonContent(ref: new Model(type: Guest::class, groups: ['guest:write']))
        ),
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', description: 'ID гостя', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Информация о госте обновлена',
                content: new OA\JsonContent(ref: new Model(type: Guest::class, groups: ['guest:read']))
            )
        ]
    )]
    public function edit(Request $request, int $id): Response
    {
        $startTime = microtime(true);

        $data = json_decode($request->getContent(), true);
        $guest = $this->guestService->getGuestById($id);
        $updatedGuest = $this->guestService->updateGuest($guest, $data);

        $response = new JsonResponse([
            'id' => $updatedGuest->getId(),
            'message' => 'Guest updated successfully'
        ], Response::HTTP_OK);

        return ResponseHelper::addDebugHeaders($response, $startTime);
    }

    #[Route('/{id}', name: 'app_guest_delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/guest/{id}',
        summary: 'Удалить гостя',
        tags: ['Guests'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', description: 'ID гостя', required: true, schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Гость удалён'
            )
        ]
    )]
    public function delete(int $id): Response
    {
        $startTime = microtime(true);

        $guest = $this->guestService->getGuestById($id);
        $this->guestService->deleteGuest($guest);

        $response = new JsonResponse([
            'message' => 'Guest deleted successfully'
        ], Response::HTTP_NO_CONTENT);

        return ResponseHelper::addDebugHeaders($response, $startTime);
    }
}
