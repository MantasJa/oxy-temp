<?php

namespace App\EventListener;

use App\Entity\User;
use App\Util\CacheTags;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\CacheInterface;

#[AsEntityListener(event: Events::postPersist, method: 'clearCache', entity: User::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'clearCache', entity: User::class)]
final class UserChangeListerner
{
    public function __construct(protected readonly CacheInterface $cache)
    {
    }

    public function clearCache(User $user, LifecycleEventArgs $event): void
    {
        $this->cache->delete(CacheTags::USER->withId($user->getId()));
    }
}
