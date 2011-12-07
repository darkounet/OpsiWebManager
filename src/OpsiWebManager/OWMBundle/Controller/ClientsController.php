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
		$created = array(
			"annee" => substr($client['created'], 0, -10),
			"mois"	=> substr($client['created'], 4, -8),
			"jours" => substr($client['created'], 6, -6),
			"heures" => substr($client['created'], 8, -4),
			"minutes" => substr($client['created'], 10, -2),
			"secondes" => substr($client['created'],12)
		);
		$lastSeen = array(
			"annee" => substr($client['lastSeen'], 0, -10),
			"mois"	=> substr($client['lastSeen'], 4, -8),
			"jours" => substr($client['lastSeen'], 6, -6),
			"heures" => substr($client['lastSeen'], 8, -4),
			"minutes" => substr($client['lastSeen'], 10, -2),
			"secondes" => substr($client['lastSeen'],12)
		);

		return $this->render('OWMBundle:Clients:detail.html.twig',array('client' => $client, 'created' => $created, 'lastSeen' => $lastSeen)); 		
	}
	public function supprimerAction($client)
	{
		shell_exec("sudo /usr/bin/opsi-admin -d method host_delete ".$client);
		$this->get('session')->setFlash('notice',$client." supprimer avec succès !!");
		return $this->redirect($this->generateUrl('Clients_voir'));
		

	}
	public function processAction()
	{
$action = $this->getRequest()->get('client_action');

if ($action == 'Supprimer')
{


$clients = $this->getRequest()->get('clients');
$i=0;
foreach ($clients as $client)
{
	shell_exec("sudo /usr/bin/opsi-admin -d method host_delete ".$client);
$i++;
}
$this->get('session')->setFlash('notice',$i." clients supprimer avec succès !!");
	return $this->redirect($this->generateUrl('Clients_voir'));
}
return $this->redirect($this->generateUrl('Clients_voir'));
	}
	public function creerAction(Request $request)
	{
		$domain=shell_exec("sudo /usr/bin/opsi-admin -Sd method getDomain");
		$defaultData = array('Domain' => $domain);
		$form = $this->createFormBuilder($defaultData)
			->add('ClientName', 'text')
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
