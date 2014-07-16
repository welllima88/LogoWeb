<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 12:54
 */

namespace Cib\Bundle\ActivityBundle\Controller;


use Cib\Bundle\ActivityBundle\Form\SignboardType;
use Cib\Bundle\ActivityBundle\Form\StoreType;
use Cib\Bundle\ActivityBundle\Form\TpeType;
use Proxies\__CG__\Cib\Bundle\ActivityBundle\Entity\Store;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;

class ActivityController extends Controller
{

    /**
     * @Route("loggedin/activity/list", name="listActivity")
     *
     * @Template()
     */
    public function listActivityAction(Request $request)
    {
        return $this->render('CibActivityBundle:Activity:listActivity.html.twig');
    }

    /**
     * @param Request $request
     * @param $page
     * @return array
     * @Route("/loggedin/signboard/display/{page}", name="displaySignboard", defaults={"page" = 1})
     *
     * @Template()
     */
    public function displaySignboardAction(Request $request,$page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Signboard');
        if(!$request->request->get('search'))
            $signboards = $repo->findAll();
        else
            $signboards = $repo->selectSignboardList($em,$request->request->get('txtSearch'));

        $csrf = $this->get('form.csrf_provider');
        foreach($signboards as $signboard)
            $signboard->setToken($csrf->generateCsrfToken($signboard->getSignboardId()));

        $paginator= $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $signboards,
            $page,
            10
        );


        return[
            'pagination' => $pagination,
        ];
    }

    /**
     * @param Request $request
     * @internal param \Symfony\Component\HttpFoundation\Session\Session $session
     * @return array|RedirectResponse
     * @Route("/loggedin/signboard/add", name="addSignboard")
     *
     * @Template()
     */
    public function addSignboardAction(Request $request)
    {
        $form = $this->createForm(new SignboardType());
        $form->handleRequest($request);

        if($form->isValid())
        {
            $signboard = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($signboard);
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Ajout effectué');
            $em->flush();

            return $this->redirect($this->generateUrl('displaySignboard'));
        }

        return[
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return array|RedirectResponse
     * @Route("/loggedin/signboard/edit/{id}", name="editSignboard", defaults={"id" = "0"})
     *
     * @Template()
     */
    public function editSignboardAction(Request $request,$id)
    {
        if($id == 0)
            throw $this->createNotFoundException('Cette enseigne n\'esxiste pas');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Signboard');
        $signboard = $repo->find($id);

        $form = $this->createForm(new SignboardType(),$signboard);
        $form->handleRequest($request);
        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $signboard = $form->getData();
                $em->persist($signboard);
                $em->flush();
            }
            else
            {
                throw $this->createNotFoundException('Page introuvable');
            }
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Modification(s) effectuée(s)');
            return $this->redirect($this->generateUrl('displaySignboard'));
        }

        return[
            'form' => $form->createView(),
            'id' => $signboard->getSignboardId(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @param $token
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return array|RedirectResponse
     * @Route("/loggedin/signboard/delete/{id}/{token}", name="deleteSignboard", defaults={"id" = 0,"token" = 0})
     *
     *
     */
    public function deleteSignboardAction(Request $request,$id,$token)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Signboard');


        $csrf = $this->get('form.csrf_provider');

        if($id ==0)
        {
            if($request->isMethod('POST'))
            {
                if($request->get('multiDelete'))
                {
                    foreach($request->get('multiDelete') as $signboardId)
                    {
                        $tempSignboard = $repo->find($signboardId);
                        $em->remove($tempSignboard);
                    }
                    $em->flush();
                    $this->get('session')->getFlashBag()->all();
                    $this->get('session')->getFlashBag()->add('status','Suppression effectuée');
                }

                return $this->redirect($this->generateUrl('displaySignboard'));
            }
        }
        else
        {
            $signboard = $repo->find($id);
            if($token == $csrf->generateCsrfToken($signboard->getSignboardId()))
            {
                $em->remove($signboard);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Suppression effectuée');

                return $this->redirect($this->generateUrl('displaySignboard'));
            }
            else
                throw $this->createNotFoundException('page introuvable');

        }


    }

    /**
     * @param Request $request
     * @return array
     * @Route("/loggedin/store/display", name="displayStore")
     *
     * @Template()
     */
    public function displayStoreAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Store');
        $stores = $repo->findAll();
        $csrf = $this->get('form.csrf_provider');
        foreach($stores as $store)
            $store->setToken($csrf->generateCsrfToken($store->getStoreId()));

        return[
            'stores' => $stores,
        ];
    }

    /**
     * @param Request $request
     * @return array|RedirectResponse
     * @Route("/loggedin/store/add", name="addStore")
     *
     * @Template()
     */
    public function addStoreAction(Request $request)
    {
        $form = $this->createForm(new StoreType());
        $form->handleRequest($request);

        if($form->isValid())
        {
            $store = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($store);
            $em->flush();
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Ajout effectué');

            return $this->redirect($this->generateUrl('displayStore'));
        }

        return[
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("/loggedin/store/edit/{id}", name="editStore", defaults={"id" = "0"})
     *
     * @Template()
     */
    public function editStoreAction(Request $request,$id)
    {
        if($id==0)
            throw $this->createNotFoundException('Ce magasin n\'existe pas');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Store');
        $store = $repo->find($id);

        $form = $this->createForm(new StoreType(array('signboard' => $store->getSignboard())),$store);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $store = $form->getData();
                $em->persist($store);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Modification(s) effectuée(s)');
                return $this->redirect($this->generateUrl('displayStore'));
            }
            else
            {
                throw $this->createNotFoundException('Page introuvable');
            }

        }

        return[
            'form' => $form->createView(),
            'id' => $id,
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @param $token
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return RedirectResponse
     *
     * @Route("/loggedin/store/delete/{id}/{token}", name="deleteStore")
     */
    public function deleteStoreAction(Request $request,$id,$token)
    {
        if($id == 0)
            throw $this->createNotFoundException('Ce magasin n\'esxiste pas');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Store');
        $store = $repo->find($id);

        $csrf = $this->get('form.csrf_provider');
        $tokenCompare = $csrf->generateCsrfToken($id);

        if($token == $tokenCompare)
        {
            $em->remove($store);
            $em->flush();
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Suppression effectuée');

            return $this->redirect($this->generateUrl('displayStore'));
        }
        else
            throw $this->createNotFoundException('page introuvable');

    }


    /**
     * @param Request $request
     * @return array
     * @Route("/loggedin/tpe/display", name="displayTpe")
     *
     * @Template()
     */
    public function displayTpeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Tpe');
        $tpe = $repo->findAll();
        $csrf = $this->get('form.csrf_provider');
        foreach($tpe as $tp)
            $tp->setToken($csrf->generateCsrfToken($tp->getTpeId()));

        return[
            'tpes' => $tpe,
        ];
    }


    /**
     * @param Request $request
     * @return array|RedirectResponse
     *
     * @Route("/loggedin/tpe/add", name="addTpe")
     * @Template()
     */
    public function addTpeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new TpeType());
        $form->handleRequest($request);

        if($form->isValid())
        {
            $tpe = $form->getData();
            $em->persist($tpe);
            $em->flush();
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Ajout effectué');

            return $this->redirect($this->generateUrl('displayTpe'));
        }

        return[
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @Route("/loggedin/tpe/edit/{id}", name="editTpe", defaults={"id" = "0"})
     * @Template()
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editTpeAction(Request $request,$id)
    {
        if($id == 0)
            throw $this->createNotFoundException('ce tpe n\'existe pas');

        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Tpe');
        $tpe = $repo->find($id);

        $form = $this->createForm(new TpeType(array('store' => $tpe->getStore())),$tpe);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $tpe = $form->getData();
                $em->persist($tpe);
                $em->flush();

                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Modification(s) effectuée(s)');

                return $this->redirect($this->generateUrl('displayTpe'));
            }
            else
                throw $this->createNotFoundException('page introuvable');

        }

        return [
            'form' => $form->createView(),
            'id' => $id,
        ];
    }


    /**
     * @param Request $request
     * @param $id
     *
     * @param $token
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/tpe/delete/{id}/{token}", name="deleteTpe")
     */
    public function deleteTpeAction(Request $request, $id, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Tpe');
        $tpe = $repo->find($id);

        $csrf = $this->get('form.csrf_provider');
        $tokenTest = $csrf->generateCsrfToken($id);
        if($token == $tokenTest)
        {
            $em->remove($tpe);
            $em->flush();
            $this->get('session')->getFlashBag()->all();
            $this->get('session')->getFlashBag()->add('status','Suppression effectuée');

            return $this->redirect($this->generateUrl('displayTpe'));
        }
        else
            throw $this->createNotFoundException('page introuvable');



    }
} 