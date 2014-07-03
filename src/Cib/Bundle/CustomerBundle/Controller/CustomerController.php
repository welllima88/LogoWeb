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
        $csrf = $this->get('form.csrf_provider');
        foreach($clients as $client)
            $client->setToken($csrf->generateCsrfToken($client->getClientId()));


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
//        $client->addCard(new Card());

        $originalCards = new ArrayCollection();

        foreach($client->getCard() as $card){
            $originalCards->add($card);
        }

//        var_dump($originalCards);
        $form = $this->createForm(new ClientType(),$client);
//        $form->handleRequest($request);

        if($request->isMethod('POST')){

            $form->bind($this->getRequest());

//            var_dump($form);die;
            if($form->isValid())
            {
//                $client = $form->getData();
                $client->setAge();
                $client->upload();
                foreach ($originalCards as $card) {
                    if ($client->getCard()->contains($card) == false) {
                        // supprime la « Task » du Tag
                        $card->setClient(null);
                        // si c'était une relation ManyToOne, vous pourriez supprimer la
                        // relation comme ceci
                        // $tag->setTask(null);

                        $em->remove($card);

                        // si vous souhaitiez supprimer totalement le Tag, vous pourriez
                        // aussi faire comme cela
                        // $em->remove($tag);
                    }
                }
//                die;
                $em->persist($client);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Modification(s) effectuée(s)');

                return $this->redirect($this->generateUrl('displayClient'));
            }
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

        $client = new Client();
        $originalCards = new ArrayCollection();

        $form = $this->createForm(new ClientType(),$client);
//        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            $form->bind($this->getRequest());

            if($form->isValid())
            {
                if($request->request->get('valider'))
                {
                    $client->setAge();
                    $client->upload();
                    foreach ($originalCards as $card) {
                        if ($client->getCard()->contains($card) == false) {
                            // supprime la « Task » du Tag
                            $card->setClient(null);
                            // si c'était une relation ManyToOne, vous pourriez supprimer la
                            // relation comme ceci
                            // $tag->setTask(null);

                            $em->remove($card);

                            // si vous souhaitiez supprimer totalement le Tag, vous pourriez
                            // aussi faire comme cela
                            // $em->remove($tag);
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
     * @Route("/loggedin/client/delete/{id}/{token}", name="deleteClient", defaults={"id" = 0, "token" = 0})
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
                        $em->remove($tempClient);
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
     * @return array
     * @Route("/loggedin/card/display", name="displayCard")
     *
     * @Template()
     */
    public function displayCardAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Card');
        $cards = $repo->findAll();
        $csrf = $this->get('form.csrf_provider');
        foreach($cards as $card)
            $card->setToken($csrf->generateCsrfToken($card->getCardId()));

        return [
            'cards' => $cards,
        ];
    }

    /**
     * @param Request $request
     *
     * @Route("/loggedin/card/edit/{id}", name="editCard")
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
//        $form->handleRequest($request);

        if($request->isMethod('POST'))
        {
            $form->bind($request);

            if($form->isValid())
            {
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
     * @Route("/loggedin/card/add", name="addCard")
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
     * @Route("/loggedin/delete/card/{id}/{token}", name="deleteCard", defaults={"id" = 0, "token" = 0})
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
                $em->flush();
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

} 