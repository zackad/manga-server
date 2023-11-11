<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \App\Controller\SettingsController
 */
class SettingsControllerTest extends WebTestCase
{
    public function testCanAccessSettingsPage()
    {
        $client = self::createClient();
        $client->request('GET', '/settings');
        self::assertResponseIsSuccessful();
    }
}
