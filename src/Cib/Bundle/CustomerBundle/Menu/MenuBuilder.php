<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 14:53
 */

namespace Cib\Bundle\CustomerBundle\Menu;

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

    public function createCustomerMenu(Request $request)
    {
        $menu = $this->factory->createItem('customer');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('Logo',array('route' => 'displayLogo'));
        //        $menu['Clients']->addChild('Clients',array('route' => 'displayClient'))->setAttribute('divider_append',true);
//        $menu['Clients']->addChild('Cartes',array('route' => 'displayCard'));
//        $menu['Clients']->addChild('Cartes',array('route' => 'displayCard'));

        return $menu;
    }
}