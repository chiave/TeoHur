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
class CategoriesController extends Controller
{
    /**
     * Lists all categories.
     *
     * @Route("/admin/categories", name="chiave_gallery_categories")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('ChiaveGalleryBundle:Categories')
            ->findBy(array(), array('createdAt' => 'DESC'));

        return array(
            'categories' => $categories,
        );
    }

    /**
     * @Route("/admin/categories/create", name="chiave_gallery_categories_create")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:Categories:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $category = new Categories();
        $form = $this->createCategoryForm(
            $category,
            'chiave_gallery_categories_create'
            );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($category);

            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_categories'));
        }

        return array(
            'category' => $category,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/admin/categories/new", name="chiave_gallery_categories_new")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:Categories:update.html.twig")
     */
    public function newAction(Request $request)
    {
        $category = new Categories();
        $form = $this->createCategoryForm(
            $category,
            'chiave_gallery_categories_create'
            );

        return array(
            'category' => $category,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing category.
     *
     * @Route("/admin/categories/{id}/edit", name="chiave_gallery_categories_edit")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:Categories:update.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('ChiaveGalleryBundle:Categories')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Categories.');
        }

        $form = $this->createCategoryForm(
            $category,
            'chiave_gallery_categories_update'
            );

        return array(
            'category'      => $category,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing category.
     *
     * @Route("/admin/categories/{id}/update", name="chiave_gallery_categories_update")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:Categories:update.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('ChiaveGalleryBundle:Categories')->find($id);

        if (!$category) {
            throw $this->createNotFoundException('Unable to find Categories.');
        }

        $form = $this->createCategoryForm(
            $category,
            'chiave_gallery_categories_update'
            );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_categories_edit', array('id' => $id)));
        }

        return array(
            'category' => $category,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes category.
     *
     * @Route("/admin/categories/{id}/delete", name="chiave_gallery_categories_delete")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $result = new \stdClass();
        $result->success = false;

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('ChiaveGalleryBundle:Categories')->find($id);

        if (!$category) {
            // throw $this->createNotFoundException('Unable to find Categories.');
            $result->error = 'Unable to find Categories.';
        } else {
            $em->remove($category);
            $em->flush();

            $result->success = true;
        }

        return new JsonResponse($result);
    }

    /**
    * Creates a form for category.
    *
    * @param Categories $category
    * @param string $route
    *
    * @return \Symfony\Component\Form\Form Form for category
    */
    public function createCategoryForm(Categories $category, $route)
    {
        return $this->createForm(
            new CategoriesType(),
            $category,
            array(
                'action' => $this->generateUrl(
                    $route,
                    array(
                        'id' => $category->getId(),
                    )),
                'method' => 'post',
            )
        );
    }
}
