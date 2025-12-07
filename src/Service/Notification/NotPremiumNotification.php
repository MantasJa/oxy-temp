<?php

namespace App\Service\Notification;

use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'not_premium_notification', priority: 20)]
final class NotPremiumNotification implements NotificationInterface
{
    /**
     * Checking if the user has a premium subscription
     */
    public function get(User $user): ?array
    {
        if (!$user->getIsPremium()) {
            return $this->getMessage();
        }

        return null;
    }

    private function getMessage(): array
    {
        return [
            'title' => 'Not a premium user',
            'description' => 'You are still not a premium user',
            'cta' => 'https://www.example.com/buy-premium',
        ];
    }
}
