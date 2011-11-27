<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Yaml\Parser;

class ClientsController extends Controller
{
	public function indexAction()
	{
		return $this->render('OWMBundle:Clients:index.html.twig');
	} 
	public function voirAction()
	{
		$clients=shell_exec("sudo opsi-admin -rd method getClients_listOfHashes");
		$yaml = new Parser();
		$clients = $yaml->parse($clients);
		return $this->render('OWMBundle:Clients:voir.html.twig',array('clients' => $clients)); 
	}
	public function detailAction($client)
	{
		$client=shell_exec("sudo opsi-admin -rd method getHost_hash ".$client);
		$yaml = new Parser();
		$client = $yaml->parse($client);
		return $this->render('OWMBundle:Clients:detail.html.twig',array('client' => $client)); 		
	}
	public function supprimerAction($client)
	{
		shell_exec("sudo /usr/bin/opsi-admin -d method host_delete ".$client);
		$this->get('session')->setFlash('notice',$client." supprimer avec succès !!");
		return $this->redirect($this->generateUrl('Clients_voir'));
		

	}
	public function creerAction(Request $request)
	{
		$domain=shell_exec("sudo /usr/bin/opsi-admin -Sd method getDomain");
		$defaultData = array('Domain' => $domain);
		$form = $this->createFormBuilder($defaultData)
			->add('ClientName', 'text')
			->add('Domain', 'text')
			->add('Description', 'textarea',array('required' => false))
			->add('Notes', 'textarea',array('required' => false))
			->add('IPaddress', 'text',array('required' => false))
			->add('MACaddress', 'text',array('required' => false))
			->getForm();


		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$data = $form->getData();

			shell_exec("sudo /usr/bin/opsi-admin -d method createClient ".$data['ClientName']." ".$data['Domain']." ".$data['Description']." ".$data['Notes']." ".$data['IPaddress']." ".$data['MACaddress']);
			if ($form->isValid()) {
				 $this->get('session')->setFlash('notice', $data['ClientName']." créer avec succès !!");
				return $this->redirect($this->generateUrl('Clients_voir'));
			}

		}

		return $this->render('OWMBundle:Clients:creer.html.twig',array('form' => $form->createView()));
	}
}
