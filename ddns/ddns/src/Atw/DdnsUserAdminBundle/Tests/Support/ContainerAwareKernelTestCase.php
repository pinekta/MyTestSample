<?php

namespace Atw\DdnsUserAdminBundle\Tests\Support;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Atw\DdnsUserAdminBundle\Tests\Support\DoctrineSupport;
use Atw\DdnsUserAdminBundle\Tests\Support\FixtureAware;

/**
 * Class ContainerAwareKernelTestCase
 */
class ContainerAwareKernelTestCase extends KernelTestCase
{
    use DoctrineSupport;
    use FixtureAware;

    /** @var  ContainerInterface */
    protected $container;

    /** @var  ValidatorInterface */
    private $validator;

    /**
     *
     */
    public function setUp()
    {
        static::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->validator = $this->container->get('validator');
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }
}
