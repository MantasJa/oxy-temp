<?php

namespace Functional\Controller;

use App\Entity\Device;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotificationControllerTest extends WebTestCase
{

    const string ENDPOINT_ROUTE = 'notifications';
    private EntityManagerInterface $em;

    private KernelBrowser $client;
    private UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();
        $this->urlGenerator = $this->client->getContainer()->get(UrlGeneratorInterface::class);

        $this->em->getConnection()->beginTransaction();
    }

    public function testNotificationsWithoutId()
    {
        $this->client->request('GET', $this->urlGenerator->generate(self::ENDPOINT_ROUTE));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testNotificationsWithNonExistingId()
    {
        $this->client->request('GET', $this->urlGenerator->generate(self::ENDPOINT_ROUTE), ['id' => 999]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testNotificationsCreatedUser()
    {
        $activityDaysThreshold = UserRepository::DEFAULT_DAYS_UNTIL_INACTIVE + 1;

        $user = (new User())->setEmail('test@test.com')
            ->setCountryCode('ES')
            ->setIsPremium(false)
            ->setStatus('active')
            ->setLastActiveAt(new \DateTime("-{$activityDaysThreshold} days"));

        $device = (new Device())
            ->setLabel('test')
            ->setPlatform('android')
            ->setUser($user);
        $user->addDevice($device);

        $this->em->persist($device);
        $this->em->persist($user);
        $this->em->flush();

        $this->client->request(
            'GET',
            $this->urlGenerator->generate(self::ENDPOINT_ROUTE),
            ['id' => $user->getId()]
        );

        $respData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($respData);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->rollBack();
        $this->em->close();

        parent::tearDown();
    }
}
