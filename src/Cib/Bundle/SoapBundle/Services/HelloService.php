<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 23/10/2014
 * Time: 11:40
 */
namespace Cib\Bundle\SoapBundle\Services;

class HelloService
{

    public function hello($name)
    {
        return 'Bonjour, '.$name;
    }
} 