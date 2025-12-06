<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\InactiveUserFinderInterface;
use App\Util\CacheTags;
use Psr\Cache\CacheItemPoolInterface;

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
     */
    public function findInactive(int $id): ?User
    {
        $cachedUser = $this->cacheItem->getItem(CacheTags::USER->withId($id));
        if (!$cachedUser->isHit()) {
            $user = $this->userRepository->find($id);
            if ($user) {
                $inactivityThreshold = $this->getInactivityThreshold($user);
                $isActive = $inactivityThreshold > 0;

                if ($isActive) {
                    $user = null;
                }

                // if the user is active, set the cache expiration to the remaining time until the inactivity threshold
                $cachedUser->expiresAfter($isActive ? $inactivityThreshold : null);
            }
            $cachedUser->set($user);
            $this->cacheItem->save($cachedUser);

            return $user;
        }

        return $cachedUser->get();
    }

    /**
     * Calculates cache time from the difference between last activity and inactivity threshold
     */
    private function getInactivityThreshold(User $user): int
    {
        $inactivityDays = $this->userRepository::DEFAULT_DAYS_UNTIL_INACTIVE;
        $inactivityLimit = new \DateTime("-{$inactivityDays} days");

        return $user->getLastActiveAt()->getTimestamp() - $inactivityLimit->getTimestamp();
    }
}

