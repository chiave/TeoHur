<?php

namespace Chiave\ContactBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Chiave\ContactBundle\Entity\Contacts;
use Chiave\ContactBundle\Form\ContactsType;

class FrontendController extends Controller
{

    /**
     * Render contact form.
     *
     * @Route("/contact/renderForm", name="chiave_contact_renderForm")
     * @Method("GET")
     * @Template()
     */
    public function renderFormAction()
    {
        $form = $this->createForm(
            new ContactsType(),
            new Contacts(),
            array(
                'action' => $this->generateUrl("chiave_contact_persist"),
                'method' => 'post',
            )
        );

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new contact.
     *
     * @Route("/contact/persist", name="chiave_contact_persist")
     * @Method("POST")
     */
    public function persistAction(Request $request)
    {
        $result = new \stdClass();
        $result->success = false;

        $contactForm = new Contacts();

        $form = $this->createForm(
            new ContactsType($type),
            $contactForm,
            array(
                'action' => $this->generateUrl("chiave_contact_persist"),
                'method' => 'post',
            )
        );

        $form->handleRequest($request);

        $data = $form->getData();

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contactForm);
            $em->flush();

            $from = array(
                    $contactForm->getEmail() => $contactForm->getFirstname() .
                        ' ' .
                        $contactForm->getLastname()
                  );

            $message = \Swift_Message::newInstance()
                ->setSubject('[Formularz kontaktowy] Zgłoszenie nr ' . $contactForm->getId())
                ->setFrom($from)
                ->setTo('konrad.mski@gmail.com')
                ->setBody(
                    $this->renderView(
                        'ChiaveContactBundle:Frontend:mailBody.txt.twig',
                        array('mail'    => $contactForm)
                    )
                )
            ;
            // $this->get('mailer')->send($message);

            $result->success = true;
            return new JsonResponse(
                    $result
            );
        }

        return new JsonResponse(
                $result
        );
    }
}
