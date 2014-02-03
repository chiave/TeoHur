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

use Chiave\GalleryBundle\Entity\Files;
use Chiave\GalleryBundle\Form\FilesType;

/**
 * Pages controller.
 *
 * @Route("/admin/files")
 */
class BackendFilesController extends Controller
{
    /**
     * Lists all files.
     *
     * @Route("/", name="chiave_gallery_files")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $files = $em->getRepository('ChiaveGalleryBundle:Files')
            ->findBy(array(), array('createdAt' => 'DESC'));

        return array(
            'files' => $files,
        );
    }

    /**
     * @Route("/create", name="chiave_gallery_files_create")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendFiles:update.html.twig")
     */
    public function createAction(Request $request)
    {
        $file = new Files();
        $form = $this->createFileForm(
            $file,
            'chiave_gallery_files_create'
            );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $em->persist($file);

            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_files'));
        }

        return array(
            'file' => $file,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/new", name="chiave_gallery_files_new")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendFiles:update.html.twig")
     */
    public function newAction(Request $request)
    {
        $file = new Files();
        $form = $this->createFileForm(
            $file,
            'chiave_gallery_files_create'
            );

        return array(
            'file' => $file,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing file.
     *
     * @Route("/{id}/edit", name="chiave_gallery_files_edit")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template("ChiaveGalleryBundle:BackendFiles:update.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('ChiaveGalleryBundle:Files')->find($id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find Files.');
        }

        $form = $this->createFileForm(
            $file,
            'chiave_gallery_files_update'
            );

        return array(
            'file'      => $file,
            'form'   => $form->createView(),
        );
    }

    /**
     * Edits an existing file.
     *
     * @Route("/{id}/update", name="chiave_gallery_files_update")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $file = $em->getRepository('ChiaveGalleryBundle:Files')->find($id);

        if (!$file) {
            throw $this->createNotFoundException('Unable to find Files.');
        }

        $form = $this->createFileForm(
            $file,
            'chiave_gallery_files_update'
            );
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('chiave_gallery_files_edit', array('id' => $id)));
        }

        return array(
            'file' => $file,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes file.
     *
     * @Route("/{id}/delete", name="chiave_gallery_files_delete")
     * @Method("POST")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction(Request $request, $id)
    {
        $result = new \stdClass();
        $result->success = false;

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('ChiaveGalleryBundle:Files')->find($id);

        if (!$file) {
            // throw $this->createNotFoundException('Unable to find Files.');
            $result->error = 'Unable to find Files.';
        } else {
            $em->remove($file);
            $em->flush();

            $result->success = true;
        }

        return new JsonResponse($result);
    }

    // /**
    //  * Download file.
    //  *
    //  * @Route("/download/{filename}", name="chiave_gallery_files_download")
    //  * @Method("GET")
    //  */
    // public function downloadAction(Request $request, $filename)
    // {
    //     $em = $this->getDoctrine()->getManager();

    //     $file = $em->getRepository('ChiaveGalleryBundle:Files')
    //             ->findOneByPath($filename);

    //     if (!$file) {
    //         throw $this->createNotFoundException('Unable to find Files.');
    //     }

    //     $path = $file->getAbsolutePath();
    //     $content = file_get_contents($path);

    //     $response = new Response();

    //     $response->headers->set('Content-Type', $file->getMimeType());
    //     $response->headers->set(
    //             'Content-Disposition',
    //             'attachment;filename="'.
    //                 $file->getName().'.'.
    //                 $file->getExtension()
    //         );

    //     $response->setContent($content);
    //     return $response;
    // }


    /**
    * Creates a form for file.
    *
    * @param Files $file
    * @param string $route
    *
    * @return \Symfony\Component\Form\Form Form for file
    */
    public function createFileForm(Files $file, $route)
    {
        return $this->createForm(
            new FilesType(),
            $file,
            array(
                'action' => $this->generateUrl(
                    $route,
                    array(
                        'id' => $file->getId(),
                    )),
                'method' => 'post',
            )
        );
    }
}
