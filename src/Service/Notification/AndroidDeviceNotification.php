<?php

namespace App\Service\Notification;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'android_device_notification', priority: 30)]
class AndroidDeviceNotification implements NotificationInterface
{
    protected const string PLATFORM_LABEL = 'android';

    public function __construct(protected DeviceRepository $deviceRepository)
    {}

    public function get(User $user): ?array
    {
        // check if user has required device
        $device = $this->deviceRepository->userHasDevice($user, self::PLATFORM_LABEL);
        if (!$device) {
            return [
                'title' => 'Android: asdfasf asdfasf',
                'description' => 'asdjf asdfj alsdfja sldfka sdf',
                'cta' => 'http://www.cta.com/android',
            ];
        }

        return null;
    }
}
