<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Yaml\Parser;

class PaquetsController extends Controller
{
	public function indexAction()
	{
		$nbnet=shell_exec("sudo /usr/bin/opsi-admin -Sd method getNetBootProductIds_list | wc -l");
		$nblocal=shell_exec("sudo /usr/bin/opsi-admin -Sd method getLocalBootProductIds_list | wc -l");
		return $this->render('OWMBundle:Paquets:index.html.twig', array('NB_Local' => $nblocal, 'NB_Net' => $nbnet));
	} 
	public function voirAction()
	{
		$paquets=shell_exec("sudo /usr/bin/opsi-admin -rd method productOnDepot_getHashes");
		$yaml = new Parser();
		$paquets = $yaml->parse($paquets);
		return $this->render('OWMBundle:Paquets:voir.html.twig',array('paquets' => $paquets)); 
	}
	public function detailAction($pkg){
		$paquets=shell_exec("sudo /usr/bin/opsi-admin -rd method getProduct_hash '".$pkg."'");
		$yaml = new Parser();
		$paquets = $yaml->parse($paquets);
		return $this->render('OWMBundle:Paquets:detail.html.twig',array('paquets' => $paquets));
	}
	public function creerAction(Request $request)
	{
	$defaultData = array();
		$form = $this->createFormBuilder($defaultData)
			->add('type', 'choice', array('choices' => array('LocalBoot', 'NetBoot')))
			->add('productId', 'text')
			->add('productName', 'text')
			->add('description', 'textarea',array('required' => false))
			->add('advice', 'text',array('required' => false))
			->add('productVersion', 'text')
			->add('packageVersion', 'text')
			->add('licence', 'text',array('required' => false))
			->add('priority', 'text',array('required' => false))
			->add('install','checkbox',array('label' => 'install','required' => false))
			->add('uninstall','checkbox',array('label' => 'uninstall','required' => false))
			->add('update','checkbox',array('label' => 'update','required' => false))
			->add('always','checkbox',array('label' => 'always','required' => false))
			->add('once','checkbox',array('label' => 'once','required' => false))
			->add('custom','checkbox',array('label' => 'custom','required' => false))
			->add('logon','checkbox',array('label' => 'logon','required' => false))
			->getForm();


		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$data = $form->getData();

			if ($form->isValid()) {
				 $this->get('session')->setFlash('notice', $data['productId']." créer avec succès !!");
				return $this->redirect($this->generateUrl('Paquets_voir'));
			}

		}

		return $this->render('OWMBundle:Paquets:creer.html.twig',array('form' => $form->createView()));
	}
	public function compilerAction(Request $request)
	{
		$pkg = scandir('/home/opsiproducts/sandbox/');
		return $this->render('OWMBundle:Paquets:compiler.html.twig',array('paquets' => $pkg));
	}
	
}
