<?php

namespace App\Notification;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;


#[AsTaggedItem(index: 'user_from_spain_notification', priority: 10)]
class UserFromSpainNotification implements NotificationInterface
{
    protected const string COUNTRY_CODE = 'ES';

    public function __construct(protected DeviceRepository $deviceRepository)
    {}

    public function get(User $user): ?array
    {
        if ($user->getCountryCode() !== self::COUNTRY_CODE) {
            return [
                'title' => 'Spain detected: asdfasf asdfasf',
                'description' => 'asdjf asdfj alsdfja sldfka sdf',
                'cta' => 'http://www.cta.com/spain',
            ];
        }

        return null;
    }
}
