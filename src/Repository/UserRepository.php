<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements InactiveUserFinderInterface
{
    public const int DEFAULT_DAYS_UNTIL_INACTIVE = 7;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param int $id
     * @param int $daysInactive
     * @return User|null
     */
    public function findInactive(int $id, int $daysInactive = self::DEFAULT_DAYS_UNTIL_INACTIVE): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->andWhere('u.last_active_at <= :since')
            ->setParameter('id', $id)
            ->setParameter('since', new \DateTime("-{$daysInactive} days"))
            ->getQuery()
            ->getOneOrNullResult();
    }
}
