<?php

namespace WeProvide\ConfigBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseAdmin extends AbstractAdmin
{
    protected $invalidCache = false;

    /**
     * Always check if cache is invalid and show warning flash message to clear cache.
     */
    public function configure()
    {
        $em           = $this->modelManager->getEntityManager('WeProvideConfigBundle:ConfigValue');
        $repo         = $em->getRepository('WeProvideConfigBundle:ConfigValue');
        $configValues = $repo->findAll();

        $em           = $this->modelManager->getEntityManager('WeProvideConfigBundle:ConfigCache');
        $repo         = $em->getRepository('WeProvideConfigBundle:ConfigCache');
        $invalidCache = $repo->findOneBy(array('valid' => false));

        if ($invalidCache && $configValues) {
            $this->invalidCache = true;
            /** @var Session $session */
            $session = $this->getConfigurationPool()->getContainer()->get('session');
            /** @var FlashBag $flashBag */
            $flashBag = $session->getFlashBag();

            if (!$flashBag->has('warning_cache')) {
                $flashBag->add('warning_cache', "Config values are changed, you'll need to clear the cache in order to see the changes.");
            }
        }

        parent::configure();
    }

    /**
     * @param RouteCollection $collection
     */
    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('recache');
    }

    /**
     * @param      $action
     * @param null $object
     * @return array
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);
        if ($this->invalidCache) {
            $list['recache'] = array(
                'template' => 'WeProvideConfigBundle:ConfigAdmin:action_recache.html.twig',
            );
        }

        return $list;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);
    }
}