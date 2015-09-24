<?php

namespace A5sys\MinkContext\Traits;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;

/**
 *
 * @author Thomas BEAUJEAN
 */
trait OrmPurgeTrait
{
    /**
     * Purge the complete database
     *
     * @param string $environment
     * @return Kernel $kernel
     */
    public static function purgeDatabase($environment = 'test')
    {
        $kernel = new \AppKernel($environment, true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $doctrine = $container->get('doctrine');
        $em = $doctrine->getManager();

        $loader = new Loader();
        $purger = new ORMPurger();
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());

        return $kernel;
    }
}
