<?php

namespace Docler\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * home controller
 */
class DefaultController extends Controller {

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        return $this->render('DoclerUserBundle:Default:index.html.twig');
    }
}
