<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 02/07/14
 * Time: 11:22
 */

namespace Cib\Bundle\CoreBundle\Menu;


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

    public function createParametersMenu(Request $request)
    {
        $menu = $this->factory->createItem('parameters');
        $menu->setChildrenAttribute('class','navbar navbar-right list-inline');
        $menu->addChild('Parametres',array('route' => 'displayParameters'));

        return $menu;
    }
} 