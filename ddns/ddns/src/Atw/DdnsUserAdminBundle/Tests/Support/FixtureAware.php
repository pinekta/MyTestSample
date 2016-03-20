<?php

namespace Atw\DdnsUserAdminBundle\Tests\Support;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

/**
 * Trait FixtureAware
 */
trait FixtureAware
{
    /** @var bool  */
    private $isFixtureAwareAlreadyPurged = false;

    /**
     *
     */
    protected function fixtures()
    {
        $container = $this->getContainer();
        $fixtures = func_get_args();
        $loader = new ContainerAwareLoader($container);
        foreach ($fixtures as $fixture) {
            $loader->addFixture($fixture);
        }
        if ($this->isFixtureAwareAlreadyPurged) {
            $executor = new ORMExecutor($container->get('doctrine')->getManager());
            $executor->execute($loader->getFixtures(), true);
        } else {
            $purger = new ORMPurger();
            $executor = new ORMExecutor($container->get('doctrine')->getManager(), $purger);
            $executor->execute($loader->getFixtures());
            $this->isFixtureAwareAlreadyPurged = true;
        }
    }
}
