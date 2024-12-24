<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;

class MainE2ETest extends PantherTestCase
{
    public function testHomePageDisplaysTitleandMenuContainer()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $client->waitFor('#home > .title > h2');
        $this->assertSelectorTextContains('#home > .title > h2', 'RESTAURANT LE VINGTIÈME');
        $this->assertSelectorTextContains('#menu-title-container > .title > h2', 'MENU');
    }
}
