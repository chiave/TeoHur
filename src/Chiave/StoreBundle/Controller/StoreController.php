<?php

namespace Chiave\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Chiave\StoreBundle\Entity\Kategoria;
use Chiave\StoreBundle\Form\KategoriaType;

class StoreController extends Controller
{
    public function addCategoryAction(Request $request)
    {
    	$kategoria = new Kategoria();
    	$form = $this->createForm(new KategoriaType(), $kategoria);
    	$form->handleRequest($request);
    	if ($form->isValid()) 
    	{
    		$em = $this->getDoctrine()->getManager();
    		$em->persist($kategoria);
    		$em->flush();
    		return $this->render('ChiaveStoreBundle:Store:testCategory.html.twig');
    	}
    	
    	else
    	{
    		return $this->render('ChiaveStoreBundle:Store:addCategory.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
}
}
