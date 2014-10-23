<?php

namespace Cib\Bundle\SoapBundle\Controller;

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
        $webPath = $this->get('kernel')->getRootDir().'/../web/cibsoap/soapServer.wsdl';
        var_dump($webPath);
        $server = new \SoapServer($webPath);

        $server->setObject($this->get('hello_service'));

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml; charset=ISO-8859-1');

        ob_start();
        $server->handle();
        $response->setContent(ob_get_clean());

        return $response;
    }
}
