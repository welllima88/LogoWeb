<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 11:23
 */

namespace Cib\Bundle\UserBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class MenuBuilder
{

    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createLoginMenu(Request $request)
    {
        $menu = $this->factory->createItem('login');

        $menu->addChild('Connexion',array('route' => 'fos_user_security_login'));
        $menu->addChild('S\'enregistrer',array('route' => 'fos_user_registration_register'));

        return $menu;
    }

    public function createLoggedInMenu(Request $request, SecurityContext $securityContext)
    {
        $menu = $this->factory->createItem('loggedin');

        $menu->addChild($securityContext->getToken()->getUser(),array('route' => 'fos_user_profile_show'));
        $menu->addChild('Deconnexion',array('route' => 'fos_user_security_logout'));

        return $menu;
    }
} 