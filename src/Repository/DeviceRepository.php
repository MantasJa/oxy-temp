<?php

namespace App\Repository;

use App\Entity\Device;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Device>
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /**
     * @param User $user
     * @param string $platform
     * @return Device|null
     */
        public function userHasDevice(User $user, string $platform): ?Device
        {
            return $this->createQueryBuilder('d')
                ->andWhere('d.user = :user')
                ->andWhere('d.platform = :platform')
                ->setParameter('user', $user)
                ->setParameter('platform', $platform)
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
}
