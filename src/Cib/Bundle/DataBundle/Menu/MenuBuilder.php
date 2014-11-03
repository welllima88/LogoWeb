<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 16/09/14
 * Time: 10:18
 */

namespace Cib\Bundle\DataBundle\Menu;


use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;



class MenuBuilder {

    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createDataMenu(Request $request)
    {
        $menu = $this->factory->createItem('results');
        $menu->setChildrenAttribute('class','nav navbar-nav');
        $menu->addChild('Resultats')->setAttribute('dropdown',true);
        $menu['Resultats']->addChild('Transactions',array('route' => 'displayResults'))->setAttribute('divider_append',true);
        $menu['Resultats']->addChild('Compensation',array('route' => 'displayCompensation'))->setAttribute('divider_append',true);

        return $menu;
    }

} 