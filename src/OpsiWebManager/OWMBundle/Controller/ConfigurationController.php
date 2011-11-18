<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Controller
{
	public function indexAction()
	{
		return $this->render('OWMBundle:Configuration:index.html.twig');
	} 
	public function voirAction()
	{

		$yaml=shell_exec("sudo opsi-admin -d method getNetworkConfig_hash");
		$info=yaml_parse($yaml);
		return $this->render('OWMBundle:Configuration:voir.html.twig', 
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
	public function modifierAction()
	{

		$yaml=shell_exec("sudo opsi-admin -d method getNetworkConfig_hash");
		$info=yaml_parse($yaml);
		return $this->render('OWMBundle:Configuration:modifier.html.twig', 
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
			}
		}

		return new Response();

	}
}
