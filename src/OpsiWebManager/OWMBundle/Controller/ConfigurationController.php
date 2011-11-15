<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

class ConfigurationController extends Controller
{
    
    public function indexAction()
    {
	$ip=shell_exec("/sbin/ifconfig eth0 | grep 'inet addr:' | cut -d : -f 2 | awk '{print $1}'");
	$depot=shell_exec("sudo /usr/bin/opsi-admin -Sd method getDepotIds_list");
	return $this->render('OWMBundle:Configuration:index.html.twig', array('ip' => $ip,'nom_depot' => $depot));
    }
}
