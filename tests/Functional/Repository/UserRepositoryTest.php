<?php

namespace Functional\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $client;
    private UserRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get('doctrine')->getManager();
        $this->repository = static::getContainer()->get(UserRepository::class);

        $this->em->getConnection()->beginTransaction();
    }

    public function testFindInactiveUser(): void
    {
        $activityDaysThreshold = UserRepository::DEFAULT_DAYS_UNTIL_INACTIVE + 1;

        $user = (new User())
            ->setCountryCode('ES')
            ->setLastActiveAt(new \DateTime("-{$activityDaysThreshold} days"));
        $this->em->persist($user);
        $this->em->flush();

        $foundUser = $this->repository->findInactive($user->getId());
        var_dump($user->getId());
        $this->assertSame($user->getId(), $foundUser->getId());
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        $this->em->close();

        parent::tearDown();
    }
}
