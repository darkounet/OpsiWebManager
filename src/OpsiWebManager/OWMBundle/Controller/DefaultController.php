<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('OWMBundle:Default:index.html.twig', array('name' => $name));
    }
}
