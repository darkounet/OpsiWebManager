<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Yaml\Parser;

class ConfigurationController extends Controller
{
	public function indexAction()
	{
		return $this->render('OWMBundle:Configuration:index.html.twig');
	} 
	public function voirAction()
	{

		$conf=shell_exec("sudo /usr/bin/opsi-admin -rd method getNetworkConfig_hash");
		$yaml = new Parser();
		$conf  = $yaml->parse($conf);
		return $this->render('OWMBundle:Configuration:voir.html.twig', 
				array(
					'depotDrive' => $conf['depotDrive'],
					'nextBootServiceURL' => $conf['nextBootServiceURL'],
					'utilsUrl' => $conf['utilsUrl'],
					'configUrl' => $conf['configUrl'],
					'utilsDrive' => $conf['utilsDrive'],
					'opsiServer' => $conf['opsiServer'],
					'nextBootServerType' => $conf['nextBootServerType'],
					'depotUrl' => $conf['depotUrl'],
					'depotId' => $conf['depotId'],
					'configDrive' => $conf['configDrive'],
					'winDomain' => $conf['winDomain']
				     ));
	}
	public function modifierAction()
	{

		$conf=shell_exec("sudo opsi-admin -rd method getNetworkConfig_hash");
		$yaml = new Parser();
		$conf  = $yaml->parse($conf);
		return $this->render('OWMBundle:Configuration:modifier.html.twig', 
				array(
					'depotDrive' => $conf['depotDrive'],
					'nextBootServiceURL' => $conf['nextBootServiceURL'],
					'utilsUrl' => $conf['utilsUrl'],
					'configUrl' => $conf['configUrl'],
					'utilsDrive' => $conf['utilsDrive'],
					'opsiServer' => $conf['opsiServer'],
					'nextBootServerType' => $conf['nextBootServerType'],
					'depotUrl' => $conf['depotUrl'],
					'depotId' => $conf['depotId'],
					'configDrive' => $conf['configDrive'],
					'winDomain' => $conf['winDomain']
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
