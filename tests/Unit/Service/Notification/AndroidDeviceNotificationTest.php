<?php


namespace Unit\Service\Notification;

use App\Entity\Device;
use App\Entity\User;
use App\Repository\DeviceRepository;
use App\Service\Notification\AndroidDeviceNotification;
use App\Service\UserDeviceChecker;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AndroidDeviceNotificationTest extends TestCase
{

    public static function androidNotificationDataProvider(): array
    {
        return [
            [true, true],
            [false, false],
        ];
    }

    #[DataProvider('androidNotificationDataProvider')]
    public function testAndroidNotification(bool $userHasDevice, bool $hasNotification): void
    {
        $userDeviceChecker = $this->createStub(UserDeviceChecker::class);
        $userDeviceChecker->method('hasDevice')
            ->willReturn($userHasDevice);
        $result = (new AndroidDeviceNotification($userDeviceChecker))->get(new User());

        if ($hasNotification) {
            $this->assertArrayHasKey('title', $result);
        } else {
            $this->assertNull($result);
        }
    }
}
