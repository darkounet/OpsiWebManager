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
#				array(
#					'hostId' => $clients[0]['hostId'],
#					'description' => $clients[0]['description'],
#					'created' => $clients[0]['created'],
#					'inventoryNumber' => $clients[0]['inventoryNumber'],
#					'notes' => $clients[0]['notes'],
#					'hardwareAdidress' => $clients[0]['hardwareAddress'],
#					'lastSeen' => $clients[0]['lastSeen'],
#					'oneTimePassword' => $clients[0]['oneTimePassword'],
#					'depotId' => $clients[0]['depotId'],
#					'opsiHostKey' => $clients[0]['opsiHostKey'],
#					'ipAddress' => $clients[0]['ipAddress']
#				     ));
	}
	public function modifierAction()
	{

		$yaml=shell_exec("sudo opsi-admin -d method getNetworkConfig_hash");
		$info=yaml_parse($yaml);
		return $this->render('OWMBundle:Clients:modifier.html.twig', 
				array(
					'depotDrive' => $info['depotDrive'],
					'nextBootServiceURL' => $info['nextBootServiceURL'],
					'utilsUrl' => $info['utilsUrl'],
					'configUrl' => $info['configUrl'],
					'utilsDrive' => $info['utilsDrive'],
					'opsiServer' => $info['opsiServer'],
					'nextBootServerType' => $info['nextBootServerType'],
					'depotUrl' => $info['depotUrl'],
					'depotId' => $info['depotId'],
					'configDrive' => $info['configDrive'],
					'winDomain' => $info['winDomain']
				     ));

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
