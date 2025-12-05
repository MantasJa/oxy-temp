<?php


namespace Unit\Service\Notification;

use App\Entity\User;
use App\Service\Notification\NotPremiumNotification;
use PHPUnit\Framework\TestCase;

class NoPremiumNotificationTest extends TestCase
{
    public function testPremiumUser(): void
    {
        $user = new User();
        $user->setIsPremium(true);
        $user->setLastActiveAt(new \DateTime());
        $user->setCreatedAt(new \DateTime());
        $this->assertEmpty((new NotPremiumNotification())->get($user));
    }

    public function testNonPremiumUser(): void
    {
        $user = new User();
        $user->setIsPremium(false);
        $user->setLastActiveAt(new \DateTime());
        $user->setCreatedAt(new \DateTime());

        $message = (new NotPremiumNotification())->get($user);
        $this->assertIsArray($message);
        $this->assertArrayHasKey('title', $message);
    }
}
