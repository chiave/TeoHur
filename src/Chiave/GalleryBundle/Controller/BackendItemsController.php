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
use Chiave\GalleryBundle\Entity\Files;
use Chiave\GalleryBundle\Form\ItemsType;

/**
 * Pages controller.
 *
 * @Route("/admin/items")
 */
class BackendItemsController extends Controller
{
    /**
     * Lists all items.
     *
     * @Route("/", name="chiave_gallery_items")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $items = $em->getRepository('ChiaveGalleryBundle:Items')
            ->findBy(array(), array('createdAt' => 'DESC'));

        return array(
            'items' => $items,
        );
    }

    /**
     * @Route("/create", name="chiave_gallery_items_create")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendItems:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $item = new Items();
        $item->setFile(new Files());

        $form = $this->createItemForm(
            $item,
            'chiave_gallery_items_create'
            );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($item);

            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_items'));
        }

        return array(
            'item' => $item,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/new", name="chiave_gallery_items_new")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendItems:update.html.twig")
     */
    public function newAction(Request $request)
    {
        $item = new Items();
        $item->setFile(new Files());

        $form = $this->createItemForm(
            $item,
            'chiave_gallery_items_create'
            );

        return array(
            'item' => $item,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing item.
     *
     * @Route("/{id}/edit", name="chiave_gallery_items_edit")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendItems:update.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('ChiaveGalleryBundle:Items')->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Unable to find Items.');
        }

        $form = $this->createItemForm(
            $item,
            'chiave_gallery_items_update'
            );

        return array(
            'item'      => $item,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing item.
     *
     * @Route("/{id}/update", name="chiave_gallery_items_update")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $item = $em->getRepository('ChiaveGalleryBundle:Items')->find($id);

        if (!$item) {
            throw $this->createNotFoundException('Unable to find Items.');
        }

        $form = $this->createItemForm(
            $item,
            'chiave_gallery_items_update'
            );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_items_edit', array('id' => $id)));
        }

        return array(
            'item' => $item,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes item.
     *
     * @Route("/{id}/delete", name="chiave_gallery_items_delete")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $result = new \stdClass();
        $result->success = false;

        $em = $this->getDoctrine()->getManager();
        $item = $em->getRepository('ChiaveGalleryBundle:Items')->find($id);

        if (!$item) {
            // throw $this->createNotFoundException('Unable to find Items.');
            $result->error = 'Unable to find Items.';
        } else {
            $em->remove($item);
            $em->flush();

            $result->success = true;
        }

        return new JsonResponse($result);
    }

    // /**
    //  * Download item.
    //  *
    //  * @Route("/download/{itemname}", name="chiave_gallery_items_download")
    //  * @Method("GET")
    //  */
    // public function downloadAction(Request $request, $itemname)
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $item = $em->getRepository('ChiaveGalleryBundle:Items')
    //             ->findOneByPath($itemname);

    //     if (!$item) {
    //         throw $this->createNotFoundException('Unable to find Items.');
    //     }

    //     $path = $item->getAbsolutePath();
    //     $content = item_get_contents($path);

    //     $response = new Response();

    //     $response->headers->set('Content-Type', $item->getMimeType());
    //     $response->headers->set(
    //             'Content-Disposition',
    //             'attachment;itemname="'.
    //                 $item->getName().'.'.
    //                 $item->getExtension()
    //         );

    //     $response->setContent($content);
    //     return $response;
    // }


    /**
    * Creates a form for item.
    *
    * @param Items $item
    * @param string $route
    *
    * @return \Symfony\Component\Form\Form Form for item
    */
    public function createItemForm(Items $item, $route)
    {
        return $this->createForm(
            new ItemsType(),
            $item,
            array(
                'action' => $this->generateUrl(
                    $route,
                    array(
                        'id' => $item->getId(),
                    )),
                'method' => 'post',
            )
        );
    }
}
