<?php

namespace Unit\Service\Notification;

use App\Entity\Device;
use App\Entity\User;
use App\Repository\InactiveUserFinderInterface;
use App\Repository\UserRepository;
use App\Service\Exception\UserNotFoundException;
use App\Service\Notification\NotificationHandler;
use App\Service\Notification\NotPremiumNotification;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Cache\InvalidArgumentException;

class NotificationHandlerTest extends TestCase
{
    public function testExistingUser(): void
    {
        $user = new User();
        $user->setIsPremium(false);
        $user->setLastActiveAt(new \DateTime('-7 days'));
        $user->setCreatedAt(new \DateTime('-8 days'));
        $user->addDevice(
            (new Device())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setLabel('test')
                ->setPlatform('android')
        );

        $userRepository = $this->createStub(UserRepository::class);
        $userRepository->method('findInactive')->with(1)
            ->willReturn($user);
        $notificationHandler = new NotificationHandler([new NotPremiumNotification()], $userRepository);
        $notifications = $notificationHandler->getByUserId(1);
    }

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testNonExistingUser(): void
    {
        $this->expectException(UserNotFoundException::class);
        $userRepository = $this->createStub(InactiveUserFinderInterface::class);
        $userRepository->method('findInactive')
            ->willReturn(null);
        $notificationHandler = new NotificationHandler([], $userRepository);
        $notificationHandler->getByUserId(1);
    }
}
