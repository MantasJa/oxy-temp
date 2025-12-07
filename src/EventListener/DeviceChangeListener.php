<?php

namespace App\EventListener;

use App\Entity\Device;
use App\Util\CacheTags;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsEntityListener(event: Events::postRemove, entity: self::ENTITY)]
#[AsEntityListener(event: Events::postPersist, entity: self::ENTITY)]
#[AsEntityListener(event: Events::postUpdate, entity: self::ENTITY)]
final class DeviceChangeListener
{
    private const string ENTITY = Device::class;
    public function __construct(private readonly TagAwareCacheInterface $cache)
    {
    }

    public function __invoke(Device $device, LifecycleEventArgs $event): void
    {
        $this->cache->invalidateTags([CacheTags::USER_DEVICES_SEARCH_TAG->withId($device->getUser()->getId())]);
    }
}
