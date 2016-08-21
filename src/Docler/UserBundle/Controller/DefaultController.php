<?php

namespace Docler\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DoclerUserBundle:Default:index.html.twig');
    }
}
