<?php

namespace App\Service\Notification;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsTaggedItem(index: 'android_device_notification', priority: 30)]
class AndroidDeviceNotification implements NotificationInterface
{
    private const string PLATFORM_LABEL = 'android';

    public function __construct(protected DeviceRepository $deviceRepository)
    {}

    /**
     * Checking if the user has specific platform
     *
     * @param User $user
     * @return string[]|null
     */
    public function get(User $user): ?array
    {
        $device = $this->deviceRepository->userHasDevice($user, self::PLATFORM_LABEL);
        if (!$device) {
            return $this->getMessage();
        }

        return null;
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
