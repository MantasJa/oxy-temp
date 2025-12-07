<?php

namespace App\Service\Notification;

use App\Entity\User;
use App\Service\UserDeviceChecker;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'android_device_notification', priority: 30)]
class AndroidDeviceNotification implements NotificationInterface
{
    private const string PLATFORM_LABEL = 'android';

    public function __construct(protected UserDeviceChecker $deviceChecker)
    {
    }

    /**
     * Checking if the user has a specific platform
     */
    public function get(User $user): ?array
    {
        return $this->deviceChecker->hasDevice($user, self::PLATFORM_LABEL)
            ? $this->getMessage()
            : null;
    }

    private function getMessage(): array
    {
        return [
            'title' => 'Detected Android device',
            'description' => 'Maybe you should see our other android apps',
            'cta' => 'https://www.example.com/android-apps',
        ];
    }
}
