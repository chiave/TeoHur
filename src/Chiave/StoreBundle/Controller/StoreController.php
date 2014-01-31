<?php

namespace Chiave\StoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Chiave\StoreBundle\Entity\Kategoria;
use Chiave\StoreBundle\Entity\File;
use Chiave\StoreBundle\Form\KategoriaType;

/**
 * @Template()
 */

class StoreController extends Controller
{
    public function addCategoryAction(Request $request)
    {
    	$kategoria = new Kategoria();
    	$form = $this->createForm(new KategoriaType(),$kategoria);
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
	public function addFileAction(Request $request)
	{
		$file = new File();
		$form = $this->createFormBuilder($file)
			->add('name')
			->add('file')
			->add('save', 'submit')
			->getForm();
		
		
			$form->handleRequest($request);
			if ($form->isValid()) {
				$em = $this->getDoctrine()->getEntityManager();
		
				$file->upload();
				
				$em->persist($file);
				$em->flush();
		
			}
		
		
			return $this->render('ChiaveStoreBundle:Store:addFile.html.twig', array('form' => $form->createView()));
	}
	
	
	
	
}
