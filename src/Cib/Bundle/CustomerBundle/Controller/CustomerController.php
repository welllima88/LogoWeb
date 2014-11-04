<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 14:50
 */

namespace Cib\Bundle\CustomerBundle\Controller;


use Cib\Bundle\CustomerBundle\Entity\Card;
use Cib\Bundle\CustomerBundle\Entity\Client;
use Cib\Bundle\CustomerBundle\Form\CardType;
use Cib\Bundle\CustomerBundle\Form\ClientType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Unirest;


class CustomerController extends Controller
{

    /**
     * @param Request $request
     * @param $page
     * @internal param $search
     * @return array
     *
     * @Route("/loggedin/client/display/{page}", name="displayClient", defaults={ "page"  = 1,})
     * @Template()
     */
    public function displayClientAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');
        if(!$request->request->get('search'))
            $clients = $repo->findAll();
        else
            $clients = $repo->selectClientList($em,$request->request->get('txtSearch'));
        $csrf = $this->get('form.csrf_provider');
        foreach($clients as $client)
            $client->setToken($csrf->generateCsrfToken($client->getClientId()));


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $clients,
            $page,
            10
        );

//        var_dump($pagination);die;
        return[
            'pagination' => $pagination,
        ];
    }


    /**
     * @param Request $request
     * @param $id
     * @return array
     * @Route("/loggedin/display/detail/client/{id}/{page}", name="displayDetailClient", defaults={"page" = 1,})
     * @Template()
     */
    public function displayDetailClientAction(Request $request, $id,$page)
    {
        $em = $this->getDoctrine()->getManager();
        $repoClient = $em->getRepository('CibCustomerBundle:Client');
        $client = $repoClient->find($id);

        return[
            'client' => $client,
            'page' => $page,
        ];
    }

    /**
     *
     * @Route("/loggedin/admin/client/edit/{id}/{page}", name="editClient", defaults={"page" =1,})
     *
     * @Template()
     * @param Request $request
     * @param $id
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editClientAction(Request $request,$id,$page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');
        $client = $repo->find($id);

        $originalCards = new ArrayCollection();

        foreach($client->getCard() as $card){
            $originalCards->add($card);
        }

        $form = $this->createForm(new ClientType(),$client);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
        if($form->isValid())
        {
            $client->setAge();
            $client->upload();
            $bankAccount = $form->getData()->getBankAccount();
            $bankAccount->setClient($form->getData());
            $club = $form->getData()->getClub();
            $club->addClient($form->getData());
            foreach ($originalCards as $card) {
                if ($client->getCard()->contains($card) == false) {
                    $card->setClient(null);
                    $em->remove($card);
                }
            }
            $em->persist($client);
            $em->flush();
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Modification(s) effectuée(s)');

            return $this->redirect($this->generateUrl('displayClient',array('page' => $page)));
        }

        }

        return[
            'form' => $form->createView(),
            'id' => $id,
            'client' => $client,
            'page' => $page,
        ];


    }


    /**
     *
     * @Route("/loggedin/admin/client/add/", name="addClient")
     *
     * @Template()
     */
    public function addClientAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $client = new Client();
        $originalCards = new ArrayCollection();

        $form = $this->createForm(new ClientType(),$client);

        if($request->isMethod('POST'))
        {
            $form->bind($this->getRequest());

            if($form->isValid())
            {
                if($request->request->get('valider'))
                {
                    $client->setAge();
                    $client->upload();
                    $bankAccount = $form->getData()->getBankAccount();
                    $bankAccount->setClient($form->getData());
                    foreach ($originalCards as $card) {
                        if ($client->getCard()->contains($card) == false) {
                            $card->setClient(null);
                            $em->remove($card);
                        }
                    }
                    $em->persist($client);
                    $em->flush();
                    $this->get('session')->getFlashBag()->all();
                    $this->get('session')->getFlashBag()->add('status','Ajout effectué');

                    return $this->redirect($this->generateUrl('displayClient'));
                }
                else
                    throw $this->createNotFoundException('page introuvable');
            }
        }

        return[
            'form' => $form->createView(),
            'client' => $client,
        ];


    }

    /**
     * @param Request $request
     * @param $id
     * @param $token
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @internal param $ $
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/admin/client/delete/{id}/{token}", name="deleteClient", defaults={"id" = 0, "token" = 0})
     */
    public function deleteClientAction(Request $request,$id,$token)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');

        $csrf = $this->get('form.csrf_provider');
        if($id == 0)
        {
            if($request->isMethod('POST'))
            {
                if($request->get('multiDelete'))
                {
                    foreach($request->get('multiDelete') as $clientId)
                    {
                        $tempClient = $repo->find($clientId);
                        try{
                            $em->remove($tempClient);
                        }
                        catch(DBALException $e)
                        {
                            $this->get('session')->getFlashBag()->all();
                            $this->get('session')->getFlashBag()->add('status','Impossible de supprimer la carte');
                            return $this->redirect($this->generateUrl('displayClient'));
                        }

                    }
                    $em->flush();
                    $this->get('session')->getFlashBag()->all();
                    $this->get('session')->getFlashBag()->add('status','Suppression effectuée');
                }

                return $this->redirect($this->generateUrl('displayClient'));
            }
        }
        else
        {
            $client = $repo->find($id);
            if($csrf->generateCsrfToken($client->getClientId()) == $token)
            {
                $em->remove($client);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Suppression(s) effectuée(s)');
                return $this->redirect($this->generateUrl('displayClient'));
            }
            else
                throw $this->createNotFoundException('Page introuvable');
        }

    }


    /**
     * @param Request $request
     * @param $page
     * @return array
     * @Route("/loggedin/card/display/{page}", name="displayCard", defaults={"page" = 1})
     *
     * @Template()
     */
    public function displayCardAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Card');
        if(!$request->request->get('search'))
            $cards = $repo->findAll();
        else
            $cards = $repo->selectCardList($em,$request->request->get('txtSearch'));

        $csrf = $this->get('form.csrf_provider');
        foreach($cards as $card)
            $card->setToken($csrf->generateCsrfToken($card->getCardId()));

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $cards,
            $page,
            10
        );

        return [
            'cards' => $cards,
            'pagination' => $pagination,
        ];
    }

    /**
     * @param Request $request
     *
     * @Route("/loggedin/admin/card/edit/{id}", name="editCard")
     * @Template()
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return array
     */
    public function editCardAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Card');
        $card = $repo->find($id);

        $form = $this->createForm(new CardType(),$card);
        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
//            $form->bind($request);

            if($form->isValid())
            {
                $card = $form->getData();
                $transactions = $em->getRepository('CibDataBundle:Transaction')->findBy(array('card' => $card));
                foreach($transactions as $transaction)
                    $transaction->setClient($card->getClient());
                $em->persist($card);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Modification effectuée');

                return $this->redirect($this->generateUrl('displayCard'));
            }
            else
                throw $this->createNotFoundException('cette carte n\'existe pas');
        }


        return[
            'form' => $form->createView(),
            'id' => $id,
            'card' => $card,
        ];
    }


    /**
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/admin/card/add", name="addCard")
     *
     * @Template()
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addCardAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $card = new Card();
        $form = $this->createForm(new CardType(), $card);

        if($request ->isMethod('POST'))
        {
            $form->bind($request);

            if($form->isValid())
            {
                $em->persist($card);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Ajout effectué');
                return $this->redirect($this->generateUrl('displayCard'));
            }

        }

        return[
            'form' => $form->createView(),
        ];

    }

    /**
     * @param Request $request
     *
     * @param $id
     * @param $token
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/admin/delete/card/{id}/{token}", name="deleteCard", defaults={"id" = 0, "token" = 0})
     */
    public function deleteCardAction(Request $request,$id,$token)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Card');

        if($id == 0)
        {
            if($request->isMethod('POST'))
            {
                foreach($request->get('multiDelete') as $idCard)
                {
                    $card = $repo->find($idCard);
                    $em->remove($card);
                }
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Suppression(s) effectuée(s)');
                return $this->redirect($this->generateUrl('displayCard'));
            }
            else
                throw $this->createNotFoundException('Page introuvable');
        }
        else
        {
            $card = $repo->find($id);
            $csrf = $this->get('form.csrf_provider');
            if($csrf->generateCsrfToken($card->getCardId()) == $token)
            {
                $em->remove($card);
                try{
                    $em->flush();
                }
                catch(DBALException $e){
                    $this->get('session')->getFlashBag()->all();
                    $this->get('session')->getFlashBag()->add('error','Impossible de supprimer la carte : des transactions lui sont affectées');
                    return $this->redirect($this->generateUrl('displayCard'));
                }
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Suppression effectuée');

                return $this->redirect($this->generateUrl('displayCard'));
            }
            else
                throw $this->createNotFoundException('Page introuvable');
        }

    }

    /**
     * @param $id
     *
     * @return array
     * @Route("/loggedin/display/detail/card/{id}", name="displayDetailCard")
     *
     * @Template()
     */
    public function displayDetailsCardAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repoCard = $em->getRepository('CibCustomerBundle:Card');
        $repoParam = $em->getRepository('CibCoreBundle:Parameters');
        $param = $repoParam->findAll();
        $card = $repoCard->find($id);

        return[
            'card' => $card,
            'param' => $param,
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/loggedin/get/city", name="getCity")
     */
    public function getCityAction(Request $request)
    {
        if($request->isXmlHttpRequest())
        {
            $response = Unirest::get("https://us-w1.zippopotam.us/".$request->query->get('country')."/".$request->query->get('postal_code'),
                array(
                    "X-Mashape-Key" => "cHk24A6zULmshCf1JFcPG6RVx2iXp1bTrENjsn8zLhYHurk8hU"
                )
            );

            return new Response($response);
        }
    }

    /**
     *
     * @Route("/import/csv/client", name="importCsv")
     */
    public function getCsvClient()
    {
        $em = $this->getDoctrine()->getManager();
        $repoClient = $em->getRepository('CibCustomerBundle:Client');
        $handle = fopen("/home/ega/EGA.csv","r");
        $file = fread($handle,filesize("C:\\Users\\cedric\\Documents\\text.csv"));
//        var_dump($file);
        $rows = explode("\n",$file);
//        var_dump($rows);
        foreach($rows as $row)
        {
            $field = explode(";",$row);
            if($field[0] != "")
                $repoClient->setClientFromCsv($em,$field);
        }

        fclose($handle);
//        die;

        return $this->redirect($this->generateUrl('displayClient'));
    }

} 