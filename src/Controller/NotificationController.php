<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Exception\UserNotFoundException;
use App\Service\Notification\NotificationHandler;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/notifications', name: 'notification', methods: ['GET'])]
    public function index(Request $request, NotificationHandler $notificationHandler): JsonResponse
    {

        $userId = $request->query->get('id');
        if (!ctype_digit($userId)) {
            return new JsonResponse(['error' => 'Please provide numeric id'], Response::HTTP_BAD_REQUEST);
        }

        // Handling user notifications. All exception responses are handled by listener
        return $this->json($notificationHandler->getByUserId((int) $userId));
    }
}
