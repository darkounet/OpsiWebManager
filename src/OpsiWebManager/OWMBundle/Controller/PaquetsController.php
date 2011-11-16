<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Yaml\Parser;

class PaquetsController extends Controller
{
	public function indexAction()
	{
		return $this->render('OWMBundle:Paquets:index.html.twig');
	} 
	public function voirAction()
	{
		$paquets=shell_exec("sudo /usr/bin/opsi-admin -rd method getProductIds_list");
		$yaml = new Parser();
		$paquets = $yaml->parse($paquets);
		return $this->render('OWMBundle:Paquets:voir.html.twig',array('paquets' => $paquets)); 
	}
	public function detailAction($pkg){
		$paquets=shell_exec("sudo /usr/bin/opsi-admin -rd method getProduct_hash '".$pkg."'");
		$yaml = new Parser();
		$paquets = $yaml->parse($paquets);
		return $this->render('OWMBundle:Paquets:detail.html.twig',array('paquets' => $paquets));
	}
}
