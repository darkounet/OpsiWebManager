<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BackupsController extends Controller
{

	public function indexAction()
	{
		$backup = array();
		exec("/bin/ls -lh /var/lib/opsi/ntfs-images/*.*",$backup,$ret);
		
		return $this->render('OWMBundle:Backup:index.html.twig', array('backup' => $backup,'ret' => $ret));
	}
	public function supprimerAction($bk)
	{

	}
}
