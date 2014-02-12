<?php

namespace Chiave\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Contact backend controller.
 *
 * @Route("/admin")
 */
class BackendController extends Controller
{
    /**
     * Lists all contacts.
     *
     * @Route("/contact", name="chiave_contact_contacts")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $contacts = $em->getRepository('ChiaveContactBundle:Contacts')
            ->findBy(array(), array('createdAt' => 'DESC'));

        return array(
            'contacts' => $contacts,
        );
    }

    /**
     * Finds and displays contact.
     *
     * @Route("/contact/show/{id}", name="chiave_contact_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN')")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('ChiaveContactBundle:Contacts')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException('Unable to find Contacts entity.');
        }

        return array(
            'contact'      => $contact,
        );
    }
}
