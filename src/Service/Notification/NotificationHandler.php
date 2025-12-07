<?php

namespace App\Service\Notification;

use App\Repository\InactiveUserFinderInterface;
use App\Service\UserCacheService;
use App\Service\Exception\UserNotFoundException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class NotificationHandler
{
    public function __construct(
        #[AutowireIterator('app.notification')]
        protected iterable                  $handlers,
        protected readonly UserCacheService $userCache,
    )
    {
    }

    /**
     * @param int $userId
     * @return array
     * @throws UserNotFoundException
     * @throws InvalidArgumentException
     */
    public function getByUserId(int $userId): array
    {
        $user = $this->userCache->findInactive($userId);

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
