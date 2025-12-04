<?php
namespace App\Controller;

use App\Notification\NotificationHandler;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'notification', methods: ['GET'])]
    public function index(Request $request, UserRepository $userRepository, NotificationHandler $notificationHandler): JsonResponse
    {
        $userId = $request->query->get('id');
        if ($userId == null) {
            return $this->json(['error' => 'No id provided'], 500);
        }

        $user = $userRepository->find((int) $request->query->get('id'));

        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json($notificationHandler->get($user));
    }
}
