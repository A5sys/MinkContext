<?php

namespace A5sys\MinkContext\Context;

use Behat\MinkExtension\Context\MinkContext;

/**
 * A symfony2 context
 */
class SymfonyContext extends MinkContext
{
    use A5sys\MinkContext\Traits\MinkTrait;

    protected $em = null;
    protected $kernel = null;
    protected $container = null;
    protected $doctrine = null;

    /**
     * Initializes context.
     *
     * @param Kernel $kernel
     */
    public function __construct($kernel)
    {
        $this->kernel = $kernel;
        $this->container = $kernel->getContainer();
        $this->doctrine = $kernel->getContainer()->get('doctrine');
        $this->em = $this->doctrine->getManager();
    }

    /**
     *
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->em;
    }

    /**
     *
     * @return Doctrine
     */
    protected function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     *
     * @return Container
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     *
     * @return AppKernel
     */
    protected function getKernel()
    {
        return $this->kernel;
    }
}
