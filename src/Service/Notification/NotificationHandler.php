<?php

namespace App\Service\Notification;

use App\Repository\UserRepository;
use App\Service\Exception\UserNotFoundException;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class NotificationHandler
{
    public function __construct(
        #[AutowireIterator('app.notification')]
        protected iterable $handlers,
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * @throws UserNotFoundException
     */
    public function getByUserId(int $userId): array
    {
        $user = $this->userRepository->findActiveSince($userId);

        if (!$user) {
            throw new UserNotFoundException;
        }

        $notifications = [];
        // build the notifications array for this specific user
        foreach ($this->handlers as $handler) {
            $notification = $handler->get($user);
            if ($notification) {
                $notifications[] = $notification;
            }
        }

        return $notifications;
    }
}
