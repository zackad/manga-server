<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RandomControllerTest extends WebTestCase
{
    public function testCovered()
    {
        $client = self::createClient();

        $client->request('GET', '/random');

        $this->assertResponseIsSuccessful();
    }
}
