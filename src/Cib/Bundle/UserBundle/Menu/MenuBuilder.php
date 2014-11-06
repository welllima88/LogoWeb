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

    private $isAdmin = false;

    private $user;


    public function __construct(FactoryInterface $factory, SecurityContext $securityContext)
    {
        $this->factory = $factory;
        $this->user = $securityContext->getToken()->getUser();
        if($this->user != "anon.")
        {
            $roles = $this->user->getRoles();
            foreach($roles as $role){
                if($role == 'ROLE_ADMIN')
                {
                    $this->isAdmin = true;
                    break;
                }
            }
        }
    }

    public function createLoginMenu(Request $request)
    {
        $menu = $this->factory->createItem('login');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->addChild('Connexion',array('route' => 'fos_user_security_login'));


        return $menu;
    }

    public function createLoggedInMenu(Request $request, SecurityContext $securityContext)
    {
//        var_dump($securityContext->getToken()->getUser());die;
        $menu = $this->factory->createItem('loggedin');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-right');
        $menu->addChild('Deconnexion',array('route' => 'fos_user_security_logout'));
        $menu->addChild($this->user,array('route' => 'fos_user_profile_show'));
        if($this->isAdmin == true)
        {
            $menu->addChild('S\'enregistrer',array('route' => 'fos_user_registration_register'));
            $menu->addChild('Utilisateurs',array('route' => 'displayUsers'));
        }
        return $menu;
    }
} 