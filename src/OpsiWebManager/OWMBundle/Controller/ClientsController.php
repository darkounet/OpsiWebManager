<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class ClientsController extends Controller
{
	public function indexAction()
	{
		return $this->render('OWMBundle:Clients:index.html.twig');
	} 
	public function voirAction()
	{

		$yaml=shell_exec("sudo opsi-admin -d method getClients_listOfHashes");
		$clients=yaml_parse($yaml);
		return $this->render('OWMBundle:Clients:voir.html.twig',array('clients' => $clients)); 
	}
	public function modifierAction()
	{

		$yaml=shell_exec("sudo opsi-admin -d method getNetworkConfig_hash");
		$info=yaml_parse($yaml);
		return $this->render('OWMBundle:Clients:modifier.html.twig',array('clients' => $clients)); 

	}
	public function validmodifAction()
	{
		$form = new OpsiForm();

		if ('POST' === $request->getMethod()) {
			$form->bindRequest($request);

			if ($form->isValid()) {
				//fais ton traitement
			}
		}

		return new Response();

	}
}
