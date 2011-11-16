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
	public function modifierAction()
	{

		$yaml=shell_exec("sudo opsi-admin -rd method getNetworkConfig_hash");
		$info=yaml_parse($yaml);
		return $this->render('OWMBundle:Clients:modifier.html.twig',array('clients' => $clients)); 

	}

	public function creerAction(Request $request)
	{
		$domain=shell_exec("sudo /usr/bin/opsi-admin -Sd method getDomain");
		$defaultData = array('Domain' => $domain);
		$form = $this->createFormBuilder($defaultData)
			->add('ClientName', 'text')
			->add('Domain', 'text')
			->add('Description', 'textarea')
			->add('Notes', 'textarea')
			->add('IPaddress', 'text')
			->add('MACaddress', 'text')
			->getForm();


		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			// data is an array with "name", "email", and "message" keys
			$data = $form->getData();

			shell_exec("sudo /usr/bin/opsi-admin -d method createClient ".$data['ClientName']." ".$data['Domain']." ".$data['Description']." ".$data['Notes']." ".$data['IPaddress']." ".$data['MACaddress']);
			if ($form->isValid()) {
				// perform some action, such as saving the task to the database

				return $this->redirect($this->generateUrl('Clients_voir'));
			}

		}

		return $this->render('OWMBundle:Clients:creer.html.twig',array('form' => $form->createView()));
	}
}
