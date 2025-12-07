<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\DeviceRepository;
use App\Util\CacheTags;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class UserDeviceChecker
{
    public function __construct(
        private readonly DeviceRepository       $deviceRepository,
        private readonly TagAwareCacheInterface $cache,
    )
    {
    }

    /**
     * Checking if user has a specific device
     */
    public function hasDevice(User $user, string $platform): bool
    {
        return $this->cache->get(
            CacheTags::USER_DEVICE_SEARCH_KEY->withId([$platform, $user->getId()]),
            function (ItemInterface $item) use ($user, $platform) {
                $item->tag([CacheTags::USER_DEVICES_SEARCH_TAG->withId($user->getId())]);

                return !empty($this->deviceRepository->findOneByUserAndName($user, $platform));
            });
    }
}
