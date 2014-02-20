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

use Chiave\GalleryBundle\Entity\Items;
use Chiave\GalleryBundle\Form\ItemsType;

/**
 * Pages controller.
 *
 * @Route("/")
 */
class FrontendItemsController extends Controller
{
    /**
     * Get all items or items from category.
     *
     * @Route("/categoryItems", name="chiave_gallery_frontend_items")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $items = $this->getRepo()->findByCategory($categoryId);

        return array(
            'items' => $items,
        );
    }

    /**
     * Get all items or items from category.
     *
     * @Route("/categoryItems/{categoryId}", name="chiave_gallery_frontend_items_find")
     * @Method("GET")
     * @Template("ChiaveGalleryBundle:FrontendItems:index.html.twig")
     */
    public function categoryItemsAction($categoryId)
    {
        $items = $this->getRepo()->findByCategory($categoryId);

        return array(
            'items' => $items,
        );
    }

    /**
     * Render slider with random products
     *
     * @Route("/slider", name="chiave_gallery_frontend_items_slider_random")
     * @Method("GET")
     * @Template("ChiaveGalleryBundle:FrontendItems:slider.html.twig")
     */
    public function renderRandomItemsSliderAction($count = 5)
    {
        $items = $this->getRepo()->findByCategory($categoryId);

        return array(
            'items' => $items,
        );
    }

    protected function getLatestItems($count)
    {
        return $this->getRepo()->createQueryBuilder('i')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    protected function getRandomItems($count)
    {
        return $this->getRepo()->createQueryBuilder('i')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult()
        ;
    }

    protected function getRepo()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('ChiaveGalleryBundle:Items')
        ;
    }
}
