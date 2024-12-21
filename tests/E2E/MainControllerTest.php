<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class MainControllerTest extends PantherTestCase
{
    public function testHomePageDisplaysTitleandMenuContainer()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertSelectorTextContains('#home > .title > h2', 'Restaurant Le Vingtième');
        $this->assertSelectorTextContains('#menu-title-container > .title > h2', 'Menu');
    }
}
