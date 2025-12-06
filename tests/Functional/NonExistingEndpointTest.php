<?php

namespace Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class NonExistingEndpointTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testNonExistingEndpoint()
    {
        $this->client->request('GET', '/non-existing-url-1312');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
