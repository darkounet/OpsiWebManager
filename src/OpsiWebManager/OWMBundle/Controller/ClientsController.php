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
		$hwaudit=shell_exec("LANG=en_US.utf-8; sudo /usr/bin/opsi-admin -rd method getHardwareInformation_hash '".$client['hostId']."'");
		$hwaudit=$yaml->parse($hwaudit);
		$swaudit=shell_exec("LANG=en_US.utf-8; sudo /usr/bin/opsi-admin -rd method getSoftwareInformation_hash '".$client['hostId']."'");
		$swaudit=$yaml->parse($swaudit);
#		echo "<pre>";	
#		print_r($swaudit);
#		exit();
		return $this->render('OWMBundle:Clients:detail.html.twig',array('client' => $client, 'created' => $created, 'lastSeen' => $lastSeen, 'hwaudit' => $hwaudit,'swaudit' => $swaudit)); 		
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
		else if ($action == 'Message')
		{
			$clients = $this->getRequest()->get('clients');
			$message = $this->getRequest()->get('message_client');
			$i=0;
			foreach ($clients as $client)
			{
				shell_exec("sudo /usr/bin/opsi-admin -d method hostControl_showPopup '".$message."' '".$client."'");
				$i++;
			}
			$this->get('session')->setFlash('notice',"message envoyé à ".$i." avec succès !!");
			return $this->redirect($this->generateUrl('Clients_voir'));
		}
		else if ($action == 'Eteindre')
		{
			$clients = $this->getRequest()->get('clients');
			$i=0;
			foreach ($clients as $client)
			{
				shell_exec("sudo /usr/bin/opsi-admin -d method hostControl_shutdown '".$client."'");
				$i++;
			}
			$this->get('session')->setFlash('notice',$i." machines éteintes avec succès !!");
			return $this->redirect($this->generateUrl('Clients_voir'));

		}
		else if ($action == 'Redemarrer')
		{
		$clients = $this->getRequest()->get('clients');
			$i=0;
			foreach ($clients as $client)
			{
				shell_exec("sudo /usr/bin/opsi-admin -d method hostControl_reboot '".$client."'");
				$i++;
			}
			$this->get('session')->setFlash('notice',$i." machines redémarrées avec succès !!");
			return $this->redirect($this->generateUrl('Clients_voir'));
		}
		else if ($action == 'Exporter')
		{
			$clients = $this->getRequest()->get('clients');
			$i=0;
			$file="clients.csv";
			$path = __DIR__ . '/../../../../web/tmp/';
			if (!is_dir($path)) {
				mkdir($path, 0755, true);
			}

			$csv=fopen($path . $file,'w+');
			foreach ($clients as $client)
			{
				$info = shell_exec("sudo /usr/bin/opsi-admin -rd method getHost_hash ".$client);
				$yaml = new Parser();
				$info = $yaml->parse($info);
				fputcsv($csv,$info);
				$i++;
			}
			fclose($csv);
			$this->get('session')->setFlash('export',$i." clients exporter avec succès !!");
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
	public function importAction(Request $request)
	{
		$form = $this->createFormBuilder()
			->add("CSV","file")
			->getForm();
		
		
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$data = $form->getData();
			$csv = $data['CSV'];
			$lines =file($csv,FILE_SKIP_EMPTY_LINES);
			$nb = count($lines);
			foreach($lines as $data)
			{
			list($name,$domain,$description,$notes,$ipaddr,$macaddr)= explode(',',$data);
			shell_exec("sudo /usr/bin/opsi-admin -d method createClient '".$name."' '".$domain."' '".$description."' '".$notes."' '".$ipaddr."' '".$macaddr."'");
			}
			
			
			if ($form->isValid()) {
				$this->get('session')->setFlash('notice', $nb." Clients importés avec succès !!");
				return $this->redirect($this->generateUrl('Clients_voir'));
			}
		
		}
return $this->render('OWMBundle:Clients:import.html.twig',array('form' => $form->createView()));
	}
}
