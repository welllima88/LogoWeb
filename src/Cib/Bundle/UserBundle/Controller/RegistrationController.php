<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 03/07/14
 * Time: 17:20
 */

namespace Cib\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends BaseController
{
    public function registerFormAction()
    {
        $formFactory = $this->container->get('fos_user.registration.form.factory');

        $form = $formFactory->createForm();

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register_content.html.twig', array(
            'form' => $form->createView(),
        ));
    }
} 