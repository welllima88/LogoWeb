<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 12:58
 */

namespace Cib\Bundle\ActivityBundle\Menu;


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

    public function createActivityMenu(Request $request)
    {
        $menu = $this->factory->createItem('activity');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('Activité')->setAttribute('dropdown',true);
        $menu['Activité']->addChild('Enseigne',array('route' => 'displaySignboard'))->setAttribute('divider_append',true);
        $menu['Activité']->addChild('Magasin',array('route' => 'displayStore'))->setAttribute('divider_append',true);
        $menu['Activité']->addChild('Tpe',array('route' => 'displayTpe'))->setAttribute('divider_append',true);

        return $menu;
    }
}