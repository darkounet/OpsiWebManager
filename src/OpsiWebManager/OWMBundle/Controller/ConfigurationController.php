<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Controller
{
	public function indexAction()
	{
	} 
	public function voirAction()
	{

		$yaml=shell_exec("sudo opsi-admin -d method getNetworkConfig_hash");
		$info=yaml_parse($yaml);
		$depot=shell_exec("sudo /usr/bin/opsi-admin -rd method getDepotIds_list");
		return $this->render('OWMBundle:Configuration:index.html.twig', 
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
}
