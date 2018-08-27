<?php

namespace WeProvide\ConfigBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BaseAdminController extends CRUDController
{
    /**
     * Runs the Symfony command clear:cache
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function recacheAction()
    {
        $kernel      = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input  = new ArrayInput([
            'command'     => 'cache:clear',
            '--env'       => $kernel->getEnvironment(),
            '--no-warmup' => true,
        ]);
        $output = new NullOutput();
        $application->run($input, $output);

        // Remove flash message.
        $session  = $this->get('session');
        $flashBag = $session->getFlashBag();
        $flashBag->get('warning_cache');

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}