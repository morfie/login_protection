<?php

namespace Docler\UserBundle\Controller;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * security controller
 */
class SecurityController extends \FOS\UserBundle\Controller\SecurityController {

    /**
     * {@inheritDoc}
     */
    protected function renderLogin(array $data) {

        $options = [
            'csrf_field_name' => '_csrf_token',
            'csrf_token_id' => 'authenticate',
        ];

        $builder = $this->container->get('form.factory')->createNamedBuilder(NULL, FormType::class, [], $options)
            ->add('_username', EmailType::class)
            ->add('_password', PasswordType::class)
        ;

        $request = $this->getRequest();
        if ($request && $this->getBruteforceCounter()->isBlocked($request)) {
            $builder->add('_captcha', CaptchaType::class);
        }

        $data['form'] = $builder->getForm()->createView();

        return $this->render('DoclerUserBundle:Security:login.html.twig', $data);
    }

    /**
     * @return \Docler\UserBundle\BruteforceDefense\BruteforceCounter
     */
    protected function getBruteforceCounter() {
        return $this->container->get('docler_user.bruteforce_defense.counter');
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest() {
        return $this->container->get('request_stack')->getMasterRequest();
    }
}
