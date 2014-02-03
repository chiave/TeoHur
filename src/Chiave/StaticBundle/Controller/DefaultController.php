<?php

namespace Chiave\StaticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($slug)
    {
        return $this->render('ChiaveStaticBundle:Static:'.$slug.'.html.twig');
    }
}
