<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\InactiveUserFinderInterface;
use App\Util\CacheTags;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

final class CachedInactiveUserFinder implements InactiveUserFinderInterface
{
    public function __construct(
        private readonly UserRepository         $userRepository,
        private readonly CacheItemPoolInterface $cacheItem,
    )
    {
    }

    /**
     * Getting inactive user entity and setting expiration time by calculated inactivity threshold limit
     *
     * @throws InvalidArgumentException
     */
    public function findInactive(int $id): ?User
    {
        $cachedUser = $this->cacheItem->getItem(CacheTags::USER->withId($id));
        if (!$cachedUser->isHit()) {
            $user = $this->userRepository->find($id);
            if ($user) {
                $inactivityThreshold = $this->getInactivityThreshold($user);
                $isActive = $inactivityThreshold > 0;
                var_dump($inactivityThreshold);

                if ($isActive) {
                    $user = null;
                }
                var_dump($isActive);

                $cachedUser->expiresAfter($isActive ? null : $inactivityThreshold);
            }
            $cachedUser->set($user);
            $this->cacheItem->save($cachedUser);

            return $user;
        }

        return $cachedUser->get();
    }

    /**
     * Calculate cache time by difference between last activity time and inactivity threshold
     *
     * @param User $user
     * @return int
     */
    private function getInactivityThreshold(User $user): int
    {
        $inactivityDays = $this->userRepository::DEFAULT_DAYS_UNTIL_INACTIVE;
        $inactivityLimit = new \DateTime("-{$inactivityDays} days");

        return $user->getLastActiveAt()->getTimestamp() - $inactivityLimit->getTimestamp();
    }
}
