<?php

namespace App\EventListener;

use App\Entity\User;
use App\Util\CacheTags;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\CacheInterface;

#[AsEntityListener(event: Events::postRemove, entity: self::ENTITY)]
#[AsEntityListener(event: Events::postPersist, entity: self::ENTITY)]
#[AsEntityListener(event: Events::postUpdate, entity: self::ENTITY)]
final class UserChangeListener
{
    private const string ENTITY = User::class;
    public function __construct(protected readonly CacheInterface $cache)
    {
    }

    public function __invoke(User $user, LifecycleEventArgs $event): void
    {

        $this->cache->delete(CacheTags::USER->withId($user->getId()));
    }
}
