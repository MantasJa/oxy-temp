<?php

namespace Functional\Repository;

use App\Entity\Device;
use App\Entity\User;
use App\Repository\DeviceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DeviceRepositoryTest extends KernelTestCase
{
    private DeviceRepository $deviceRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->em = static::getContainer()->get('doctrine')->getManager();
        $this->deviceRepository = static::getContainer()->get(DeviceRepository::class);
    }

    public function testFindingUserWithDevice(): void
    {
        $activityDaysThreshold = UserRepository::DEFAULT_DAYS_UNTIL_INACTIVE + 1;
        $user = (new User())
            ->setCountryCode('ES')
            ->setLastActiveAt(new \DateTime("-{$activityDaysThreshold} days"));

        $device = (new Device())->setLabel('test')->setPlatform('android');
        $user->addDevice($device);
        $this->em->persist($device);
        $this->em->persist($user);
        $this->em->flush();

        $foundDevice = $this->deviceRepository->findOneByUserAndName($user, $device->getPlatform());
        $this->assertSame($foundDevice->getUser()->getId(), $user->getId());
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->close();
        $this->em->close();

        parent::tearDown();
    }
}
