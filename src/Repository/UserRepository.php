<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findInactiveUser(int $id, int $daysInactive = 7): ?User
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
