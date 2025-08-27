<?php

namespace App\Tests\E2E;

use Symfony\Component\Panther\PantherTestCase;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverWait;


class MainE2ETest extends PantherTestCase
{
    public function testHomePageDisplaysTitle()
    {
        $client = static::createPantherClient([
            'browser' => static::FIREFOX,
            'external_base_uri' => 'http://127.0.0.1:8000', // serveur Symfony lancé en APP_ENV=test
        ]);
        $crawler = $client->request('GET', '/');
        $wait = new WebDriverWait($client, 10);

        $wait->until(
            WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('loader'))
        );
        $this->assertSelectorTextContains('#home > .title > h2', 'RESTAURANT LE VINGTIÈME');
    }
}
