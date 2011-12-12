<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BackupsController extends Controller
{

	public function indexAction()
	{
		$detail = array();
		$backup = scandir('/var/lib/opsi/ntfs-images/');
		foreach ($backup as $bk) {
			if ( $bk != '.' && $bk != '..') {
			if (!preg_match('/desc-/i',$bk)) {
			$detail[$bk] = stat('/var/lib/opsi/ntfs-images/'.$bk);
			$detail[$bk]['size'] = round($detail[$bk]['size'] / 1024 / 1024 / 1024, 2 )." Go";
			$ts = $detail[$bk]['mtime'];
			$date = new \DateTime("@$ts");
			$detail[$bk]['mtime'] = $date->format('Y-m-d H:i:s');
			$fichier = "/var/lib/opsi/ntfs-images/desc-".$bk.".txt";
			if (!file_exists($fichier)) {
				touch($fichier);
				$new_fichier = fopen($fichier,"w");
				fwrite($new_fichier,'Aucune description');
				fclose($new_fichier);
			}
			$description = fread(fopen($fichier,"r"),filesize($fichier));
			$detail[$bk]['description'] = nl2br($description);
			}
			}
		}
		$key = array_keys($detail);
		return $this->render('OWMBundle:Backup:index.html.twig', array('backup' => $detail, 'keys' => $key));
	}
	public function supprimerAction($bk)
	{

	}
}
