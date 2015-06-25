<?php

namespace Cib\Bundle\SoapBundle\Controller;

use Cib\Bundle\SoapBundle\Services\RightService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class SoapController extends Controller
{
    /**
     * @Route("/Soap/Hello" , name="helloSoapService")
     *
     */
    public function helloAction()
    {
        $webPath = $this->get('kernel')->getRootDir().'/../src/Cib/Bundle/SoapBundle/Resources/public/Soap/Hello/soapServer.wsdl';
//        var_dump($webPath);
        $server = new \SoapServer($webPath);

        $server->setObject($this->get('hello_service'));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $server->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }

    /**
     * @Route("/soap/check", name="rightCheck")
    */
    public function rightAction()
    {
        $soapServer = new \SoapServer("http://localhost/LogoWeb/web/soap/check/test.wsdl");
        $soapServer->setObject($this->get('right_service'));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $soapServer->handle();

        $response->setContent(ob_get_clean());

        return $response;
    }
}
