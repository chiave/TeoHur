<?php

namespace Chiave\GalleryBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Chiave\GalleryBundle\Entity\Categories;
use Chiave\GalleryBundle\Form\CategoriesType;

/**
 * Pages controller.
 *
 * @Route("/")
 * @Security("has_role('ROLE_ADMIN')")
 */
class FrontendCategoriesController extends Controller
{
    /**
     * Lists all categories.
     *
     * @Route("/", name="chiave_gallery_frontend_categories")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em
            ->getRepository('ChiaveGalleryBundle:Categories')
            ->findBy(array(), array('createdAt' => 'DESC'));

        return array(
            'categories' => $categories,
        );
    }
}
