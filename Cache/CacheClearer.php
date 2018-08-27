<?php

namespace WeProvide\ConfigBundle\Cache;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

class CacheClearer implements CacheClearerInterface
{
    protected $em;

    /**
     * CacheClearer constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Clears any caches necessary.
     *
     * @param string $cacheDir The cache directory
     */
    public function clear($cacheDir)
    {
        $repo = $this->em->getRepository('WeProvideConfigBundle:ConfigCache');
        $repo->truncate();
    }
}