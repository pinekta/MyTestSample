<?php

namespace Atw\DdnsUserAdminBundle\Tests\Support;

use Doctrine\ORM\EntityManager;

/**
 * Trait DoctrilneSupport
 */
trait DoctrineSupport
{
    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }
}
