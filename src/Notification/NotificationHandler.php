<?php

namespace App\Notification;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

class NotificationHandler
{
    public function __construct(
        #[AutowireIterator('app.notification')]
        protected iterable $handlers
    ) {
    }

    public function get(User $user): array
    {
        $notifications = [];
        foreach ($this->handlers as $handler) {
            $notification = $handler->get($user);
            if ($notification) {
                $notifications[] = $notification;
            }
        }

        return $notifications;
    }
}
