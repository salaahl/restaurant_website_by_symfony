<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use App\DataFixtures\AppFixtures;

class DatabaseTestCase extends KernelTestCase
{
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->em = self::getContainer()->get('doctrine')->getManager();

        // Purger la base de données
        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);

        $loader = new Loader();
        $loader->addFixture(new AppFixtures());

        $executor->execute($loader->getFixtures());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->close();
        unset($this->em);
    }
}
