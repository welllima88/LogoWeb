<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 14:50
 */

namespace Cib\Bundle\CustomerBundle\Controller;


use Cib\Bundle\CustomerBundle\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class CustomerController extends Controller
{

    /**
     * @param Request $request
     * @return array
     *
     * @Route("/loggedin/client/display", name="displayClient")
     * @Template()
     */
    public function displayClientAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');
        $clients = $repo->findAll();

        return[
            'clients' => $clients,
        ];
    }



    /**
     *
     * @Route("/loggedin/client/edit/{id}", name="editClient")
     *
     * @Template()
     */
    public function editClientAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');
        $client = $repo->find($id);

        $form = $this->createForm(new ClientType(),$client);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $client = $form->getData();
                $client->setAge();
                $client->upload();
                $em->persist($client);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Modification(s) effectuée(s)');

                return $this->redirect($this->generateUrl('displayClient'));
            }
            else
                throw $this->createNotFoundException('page introuvable');

        }

        return[
            'form' => $form->createView(),
            'id' => $id,
            'client' => $client,
        ];


    }


    /**
     *
     * @Route("/loggedin/client/add/", name="addClient")
     *
     * @Template()
     */
    public function addClientAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');

        $form = $this->createForm(new ClientType());
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $client = $form->getData();
                $client->setAge();
                $client->upload();
                $em->persist($client);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Ajout effectué');

                return $this->redirect($this->generateUrl('displayClient'));
            }
            else
                throw $this->createNotFoundException('page introuvable');

        }

        return[
            'form' => $form->createView(),
        ];


    }


} 