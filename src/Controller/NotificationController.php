<?php

namespace App\Controller;

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
     * @throws UserNotFoundException
     */
    #[Route('/notifications', name: 'notification', methods: ['GET'])]
    public function index(Request $request, NotificationHandler $notificationHandler): JsonResponse
    {
        $userId = $request->query->get('id');
        if (!ctype_digit($userId)) {
            return new JsonResponse(['error' => 'Please provide numeric id'], Response::HTTP_BAD_REQUEST);
        }

        // Handling user notifications. All exception responses are handled by ExceptionListener
        return $this->json($notificationHandler->getByUserId((int) $userId));
    }
}
