<?php

namespace Unit\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\CachedInactiveUserFinder;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

class CachedInactiveUserFinderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function testFindingCachedUser(): void
    {
        $user = (new User())->setEmail('test@test.com');
        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(true);
        $cacheItem->method('get')->willReturn($user);

        $cacheItems = $this->createStub(CacheItemPoolInterface::class);
        $cacheItems->method('getItem')->willReturn($cacheItem);

        $user = (new CachedInactiveUserFinder($this->createStub(UserRepository::class), $cacheItems))
            ->findInactive(1);

        $this->assertIsObject($user);
        $this->assertNotEmpty($user->getEmail());
    }

    public function testNonExistingUser(): void
    {
        $userRepo = $this->createStub(UserRepository::class);
        $userRepo->method('find')->willReturn(null);

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $cacheItems = $this->createStub(CacheItemPoolInterface::class);
        $cacheItems->method('getItem')->willReturn($cacheItem);

        $result = (new CachedInactiveUserFinder($userRepo, $cacheItems))->findInactive(1);

        $this->assertNull($result);
    }

    public function testExistingNonCachedActiveUser(): void
    {
        $lastActive = UserRepository::DEFAULT_DAYS_UNTIL_INACTIVE - 1;
        $user = (new User())
            ->setLastActiveAt(new \DateTime("-{$lastActive} days"));

        $userRepo = $this->createStub(UserRepository::class);
        $userRepo->method('find')->willReturn($user);

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $cacheItems = $this->createStub(CacheItemPoolInterface::class);
        $cacheItems->method('getItem')->willReturn($cacheItem);

        $result = (new CachedInactiveUserFinder($userRepo, $cacheItems))->findInactive(1);

        // expecting null because user was active more than default days until inactive
        $this->assertNull($result);
    }

    public function testExistingNonCachedInactiveUser(): void
    {
        $lastActive = UserRepository::DEFAULT_DAYS_UNTIL_INACTIVE + 1;
        $user = (new User())
            ->setLastActiveAt(new \DateTime("-{$lastActive} days"));

        $userRepo = $this->createStub(UserRepository::class);
        $userRepo->method('find')->willReturn($user);

        $cacheItem = $this->createMock(CacheItemInterface::class);
        $cacheItem->method('isHit')->willReturn(false);

        $cacheItems = $this->createStub(CacheItemPoolInterface::class);
        $cacheItems->method('getItem')->willReturn($cacheItem);

        $result = (new CachedInactiveUserFinder($userRepo, $cacheItems))->findInactive(1);

        // expecting null because user was active more than default days until inactive
        $this->assertNotNull($result);
    }
}
