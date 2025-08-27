<?php

namespace App\Tests\Traits;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use App\DataFixtures\AppFixtures;

trait DatabaseTestTrait
{
    protected EntityManagerInterface $em;

    protected function loadDatabaseFixtures(): void
    {
        $this->em = self::getContainer()->get('doctrine')->getManager();

        // Purger la base
        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);

        $loader = new Loader();
        $loader->addFixture(new AppFixtures());

        $executor->execute($loader->getFixtures());
    }
}
