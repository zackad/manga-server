<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[\PHPUnit\Framework\Attributes\CoversClass(\App\Controller\SettingsController::class)]
class SettingsControllerTest extends WebTestCase
{
    public function testCanAccessSettingsPage()
    {
        $client = self::createClient();
        $client->request('GET', '/settings');
        self::assertResponseIsSuccessful();
    }

    public function testReindexJobBeingTriggered(): void
    {
        $client = self::createClient();
        $transport = self::getContainer()->get('messenger.transport.async');
        $client->request('POST', '/settings/reindex');
        self::assertResponseRedirects('/settings');
        self::assertCount(1, $transport->getSent());
    }
}
