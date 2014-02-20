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
 */
class FrontendCategoriesController extends Controller
{
    /**
     * Lists all categories.
     *
     * @Route("/all", name="chiave_gallery_frontend_categories")
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

    /**
     * Stow one category with childrens.
     *
     * @Route("/", name="chiave_gallery_frontend_categories_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction()
    {
        $categories = $this->getCategories();

        return array(
            'categories' => $categories,
        );
    }

    /**
     * Stow one category with childrens.
     *
     * @Route("/ajax/category/{id}", name="chiave_gallery_frontend_categories_ajax")
     * @Method("GET")
     * @Template()
     */
    public function getCategoriesAction($id = 0)
    {
        $result = new \stdClass();
        $result->success = false;

        $categories = $this->getCategories($id);
        foreach ($categories as $category) {
            $items = array();

            foreach ($category->getItems() as $item) {
                $items[] = array(
                    'id'            => $item->getId(),
                    'productKey'    => $item->getProductKey(),
                    'name'          => $item->getName(),
                    'description'   => $item->getDescription(),
                    'file'          => $item->getFile()->getWebPath(),
                );
            }

            $result->categories[] = array(
                    'id'            => $category->getId(),
                    'name'          => $category->getName(),
                    'description'   => $category->getDescription(),
                    'file'          => $category->getFile()->getWebPath(),
                    'items'         => $items,
                )
            ;
        }

        $result->success = true;
        return new JsonResponse($result);
    }

    protected function getCategories($id = 0)
    {
        //when id == 0 there will be only latest products
        //  currently all products
        if ($id == 0) {
            $categories = $this->getRepo()
                ->findBy(array(), array('createdAt' => 'DESC'));
        } else {
            $categories = $this->getRepo()->createQueryBuilder('c')
                ->where('c.id = :id')
                ->orWhere('c.parent = :id')
                    ->setParameter('id', $id)

                ->orderBy('c.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
            ;
        }

        return $categories;
    }

    protected function getRepo()
    {
        return $this->getDoctrine()
            ->getManager()
            ->getRepository('ChiaveGalleryBundle:Categories')
        ;
    }
}
