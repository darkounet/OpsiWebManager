<?php

namespace OpsiWebManager\OWMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class WelcomeController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('OWMBundle:Welcome:index.html.twig');
    }
}
