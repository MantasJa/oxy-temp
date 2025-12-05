<?php

namespace App\Controller\Api;

use App\Service\Exception\UserNotFoundException;
use App\Service\Notification\NotificationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    /**
     * @param Request $request
     * @param NotificationHandler $notificationHandler
     * @return JsonResponse
     * @throws UserNotFoundException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route('/notifications', name: 'notifications', methods: ['GET'])]
    public function __invoke(Request $request, NotificationHandler $notificationHandler): JsonResponse
    {
        $userId = $request->query->get('id');
        if (!ctype_digit($userId)) {
            return new JsonResponse(['error' => 'Please provide numeric id'], Response::HTTP_BAD_REQUEST);
        }

        // Handling user notifications. All exception responses are handled by listener
        return $this->json($notificationHandler->getByUserId((int) $userId));
    }
}
