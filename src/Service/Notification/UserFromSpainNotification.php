<?php

namespace App\Service\Notification;

use App\Entity\User;
use App\Repository\DeviceRepository;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;


#[AsTaggedItem(index: 'user_from_spain_notification', priority: 10)]
final class UserFromSpainNotification implements NotificationInterface
{
    private const string COUNTRY_CODE = 'ES';

    public function __construct(protected DeviceRepository $deviceRepository)
    {}

    /**
     * Checking if the user has specific country code
     *
     * @param User $user
     * @return string[]|null
     */
    public function get(User $user): ?array
    {
        if ($user->getCountryCode() !== self::COUNTRY_CODE) {
            return $this->getMessage();
        }

        return null;
    }

    private function getMessage(): array
    {
        return [
            'title' => 'Detected Spain country',
            'description' => 'We can see that you are from Spain',
            'cta' => 'https://www.example.com/offers-in-spain',
        ];
    }
}
