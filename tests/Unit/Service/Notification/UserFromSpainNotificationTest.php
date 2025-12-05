<?php

namespace Unit\Service\Notification;

use App\Entity\User;
use App\Service\Notification\UserFromSpainNotification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserFromSpainNotificationTest extends TestCase
{
    public static function userCountryCodesProvider(): array
    {
        return [
            ['US', false],
            ['ES', true],
            [123, false],
        ];
    }

    #[DataProvider('userCountryCodesProvider')]
    public function testUserCountryCode(mixed $countryCode, bool $hasMessage): void
    {
        $user = new User();
        $user->setIsPremium(true);
        $user->setLastActiveAt(new \DateTime());
        $user->setCreatedAt(new \DateTime());
        $user->setCountryCode($countryCode);
        $result = (new UserFromSpainNotification())->get($user);
        if ($hasMessage) {
            $this->assertArrayHasKey('title', $result);
        } else {
            $this->assertNull($result);
        }
    }

}
