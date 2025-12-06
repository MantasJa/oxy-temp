<?php

namespace Functional\Controller;

use App\Entity\Device;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Notification\NotificationHandler;
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

    public function testExceptionHandling()
    {
        $notificationsService = $this->createStub(NotificationHandler::class);
        $notificationsService->method('getByUserId')
            ->willThrowException(new \Exception('Does not matter what'));

        $this->client->getContainer()->set(NotificationHandler::class, $notificationsService);

        $this->makeRequest(12345);

        $respData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('error', $respData);
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
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

        $this->makeRequest($user->getId());

        $respData = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertIsArray($respData);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    protected function tearDown(): void
    {
        $this->em->getConnection()->close();
        $this->em->close();

        parent::tearDown();
    }

    /**
     * @param int $id
     * @return void
     */
    private function makeRequest(int $id): void
    {
        $this->client->request(
            'GET',
            $this->urlGenerator->generate(self::ENDPOINT_ROUTE),
            ['id' => $id]
        );
    }
}
