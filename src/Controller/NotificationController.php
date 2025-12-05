<?php

namespace App\Controller;

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

    #[Route('/test', name: 'test', methods: ['GET'])]
    public function test(Request $request, EntityManagerInterface $em, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->find((int) $request->query->get('id'));
        if (!$user) {
            throw new UserNotFoundException();
        }
        $user->setIsPremium((bool) rand(0, 1));

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }
}
