<?php

namespace App\Tests\Integration\Controller;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Repository\MenuRepository;
use App\Repository\DishRepository;

class MainControllerIntegrationTest extends KernelTestCase
{
    public function testHomePageWithDatabaseData()
    {
        self::bootKernel();
        $container = static::getContainer();

        $menuRepository = $container->get(MenuRepository::class);
        $dishRepository = $container->get(DishRepository::class);

        $menus = $menuRepository->findBy([], ['id' => 'ASC']);
        $dishs = $dishRepository->findBy([], ['id' => 'ASC']);

        $this->assertNotEmpty($menus);
        $this->assertNotEmpty($dishs);
    }
}
