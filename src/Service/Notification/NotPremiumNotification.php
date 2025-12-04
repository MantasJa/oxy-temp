<?php

namespace App\Service\Notification;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;


#[AsTaggedItem(index: 'not_premium_notification', priority: 20)]
class NotPremiumNotification implements NotificationInterface
{
    public function __construct(protected DeviceRepository $deviceRepository)
    {}

    public function get(User $user): ?array
    {
        if (!$user->getIsPremium()) {
            return [
                'title' => 'Not premium: asdfasf asdfasf',
                'description' => 'asdjf asdfj alsdfja sldfka sdf',
                'cta' => 'http://www.cta.com/spain',
            ];
        }

        return null;
    }
}
