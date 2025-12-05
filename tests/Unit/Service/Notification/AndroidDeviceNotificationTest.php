<?php


namespace Unit\Service\Notification;

use App\Entity\Device;
use App\Entity\User;
use App\Repository\DeviceRepository;
use App\Service\Notification\AndroidDeviceNotification;
use PHPUnit\Framework\TestCase;

class AndroidDeviceNotificationTest extends TestCase
{
    public function testNonAndroidUser(): void
    {
        $deviceRepo = $this->createStub(DeviceRepository::class);
        $deviceRepo->method('userHasDevice')
            ->willReturn(null);
       $result = (new AndroidDeviceNotification($deviceRepo))->get(new User());
       $this->assertNull($result);
    }

    public function testAndroidUser(): void
    {
        $device =  (new Device())
            ->setCreatedAt(new \DateTimeImmutable())
            ->setLabel('test')
            ->setPlatform('android');

        $deviceRepo = $this->createStub(DeviceRepository::class);
        $deviceRepo->method('userHasDevice')
            ->willReturn($device);
       $result = (new AndroidDeviceNotification($deviceRepo))->get(new User());
       $this->assertArrayHasKey('title', $result);
    }
}
