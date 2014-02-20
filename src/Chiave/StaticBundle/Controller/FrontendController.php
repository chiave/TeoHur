<?php

namespace Chiave\StaticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Frontend controller.
 *
 * @Route("")
 */
class FrontendController extends Controller
{
    /**
     * Main page render action
     *
     * @Route("", name="chiave_static_main")
     * @Method("GET")
     */
    public function mainPageAction()
    {
        return $this->render('ChiaveStaticBundle:Frontend:glowna.html.twig');
    }

    /**
     * Static pages render action
     *
     * @Route("/{slug}", name="chiave_static", defaults={"slug" = ""})
     * @Method("GET")
     */
    public function indexAction($slug)
    {
        $slug != '' ? null : $slug = 'glowna';

        return $this->render('ChiaveStaticBundle:Frontend:'.$slug.'.html.twig');
    }
}
