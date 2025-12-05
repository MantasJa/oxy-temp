<?php

namespace App\EventListener;

// ...
use App\Entity\User;
use App\Util\CacheTags;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\CacheItem;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: User::class)]
final class UserChangeListerner
{
    public function __construct(protected readonly TagAwareCacheInterface $cache)
    {
    }

    public function postUpdate(User $user, PostUpdateEventArgs $event): void
    {
        $this->cache->delete(CacheTags::USER->withId($user->getId()));
    }
}
