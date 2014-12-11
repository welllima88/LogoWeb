<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 12:54
 */

namespace Cib\Bundle\ActivityBundle\Controller;


use Cib\Bundle\ActivityBundle\Entity\Tpe;
use Cib\Bundle\ActivityBundle\Entity\tpeParameters;
use Cib\Bundle\ActivityBundle\Form\SignboardType;
use Cib\Bundle\ActivityBundle\Form\StoreType;
use Cib\Bundle\ActivityBundle\Form\TpeType;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Route("/loggedin/admin/signboard/add", name="addSignboard")
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
     * @Route("/loggedin/admin/signboard/edit/{id}", name="editSignboard", defaults={"id" = "0"})
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
     * @Route("/loggedin/admin/signboard/delete/{id}/{token}", name="deleteSignboard", defaults={"id" = 0,"token" = 0})
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
     * @param $page
     * @return array
     * @Route("/loggedin/store/display/{page}", name="displayStore", defaults={"page" = 1})
     *
     * @Template()
     */
    public function displayStoreAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Store');
        if(!$request->request->get('search'))
            $stores = $repo->findAll();
        else
            $stores = $repo->selectStoreList($em, $request->request->get('txtSearch'));

        $csrf = $this->get('form.csrf_provider');
        foreach($stores as $store)
            $store->setToken($csrf->generateCsrfToken($store->getStoreId()));

        $paginator= $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $stores,
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
     *
     * @Route("/loggedin/store/display/detail/{id}/{token}", name="displayDetailStore")
     *
     * @Template()
     */
    public function displayDetailStoreAction(Request $request, $id, $token)
    {
        if($id != 0)
        {

            $em = $this->getDoctrine()->getManager();
            $repoStore = $em->getRepository('CibActivityBundle:Store');
            $store = $repoStore->find($id);
            $csrf = $this->get('form.csrf_provider');

            if($token == $csrf->generateCsrfToken($store->getStoreId()))
            {
//                var_dump($store);die;
                return[
                    'store' => $store
                ];
            }
            else
                throw $this->createNotFoundException("Page introuvable");
        }
        else
            throw $this->createNotFoundException("Page introuvable");
    }

    /**
     * @param Request $request
     * @return array|RedirectResponse
     * @Route("/loggedin/admin/store/add", name="addStore")
     *
     * @Template()
     */
    public function addStoreAction(Request $request)
    {
        $store = new \Cib\Bundle\ActivityBundle\Entity\Store();
        $form = $this->createForm(new StoreType(),$store);
        $form->handleRequest($request);
        $originalTpe = new ArrayCollection();

        if($request->isMethod('POST'))
        {
            if($form->isValid())
            {
                $store = $form->getData();
                $em = $this->getDoctrine()->getManager();
                foreach ($originalTpe as $tpe) {
                    if ($store->getTpe()->contains($tpe) == false) {
                        $tpe->setStore(null);
                        $em->remove($tpe);
                    }
                }
                $em->persist($store);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Ajout effectué');

                return $this->redirect($this->generateUrl('displayStore'));
            }
        }

        return[
            'form' => $form->createView(),
            'store' => 'store',
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @Route("/loggedin/admin/store/edit/{id}", name="editStore", defaults={"id" = "0"})
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
        $originalTpe = new ArrayCollection();

        foreach($store->getTpe() as $tpe){
            $originalTpe->add($tpe);
        }

        $form = $this->createForm(new StoreType(array('signboard' => $store->getSignboard())),$store);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $store = $form->getData();
                foreach ($originalTpe as $tpe) {
                    if ($store->getTpe()->contains($tpe) == false) {
                        $tpe->setStore(null);
                        $em->remove($tpe);
                    }
                    else
                        $tpe->setStore($store);
                }
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
            'store' => 'store',
        ];
    }

    /**
     * @param Request $request
     * @param $id
     * @param $token
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return RedirectResponse
     *
     * @Route("/loggedin/admin/store/delete/{id}/{token}", name="deleteStore", defaults={"id" = 0, "token" = 0})
     */
    public function deleteStoreAction(Request $request,$id,$token)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Store');

        $csrf = $this->get('form.csrf_provider');

        if($id == 0)
        {
            if($request->isMethod('post'))
            {
                if($request->get('multiDelete'))
                {
                    foreach($request->get('multiDelete') as $storeId)
                    {
                        $tempStore = $repo->find($storeId);
                        $em->remove($tempStore);
                    }
                    $em->flush();
                    $this->get('session')->getFlashBag()->all();
                    $this->get('session')->getFlashBag()->add('status','Suppression effectuée');
                }

                return $this->redirect($this->generateUrl('displayStore'));
            }
        }
        else
        {
            $store = $repo->find($id);
            if($token == $csrf->generateCsrfToken($store->getStoreId()))
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


    }


    /**
     * @param Request $request
     * @param $page
     * @return array
     * @Route("/loggedin/tpe/display/{page}", name="displayTpe", defaults={"page" = 1})
     *
     * @Template()
     */
    public function displayTpeAction(Request $request,$page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Tpe');

        if(!$request->request->get('search'))
            $tpe = $repo->findAll();
        else
            $tpe = $repo -> selectTpeList($em, $request->request->get('txtSearch'));
        $csrf = $this->get('form.csrf_provider');
        foreach($tpe as $tp)
            $tp->setToken($csrf->generateCsrfToken($tp->getTpeId()));

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $tpe,
            $page,
            10
        );

        return[
            'pagination' => $pagination,
        ];
    }


    /**
     * @param Request $request
     * @return array|RedirectResponse
     *
     * @Route("/loggedin/admin/tpe/add", name="addTpe")
     * @Template()
     */
    public function addTpeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tpe = new Tpe();
        $param = $em->getRepository('CibCoreBundle:Parameters')->find(1);
        $ftp = new Ftp($param->getFtpUrl(),$param->getFtpUser(),$param->getFtpPassword(),$param->getFtpPort(),false,false);
        $form = $this->createForm(new TpeType(),$tpe);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $tpe = $form->getData();
            $tpe->getTpeParameters()->createParameterFile($tpe);
            if($tpe->uploadParameterFile($ftp,$request))
                $this->get('session')->getFlashBag()->add('ftpSuccess','envoi du fichier de paramétrage réussi');
            else
                $this->get('session')->getFlashBag()->add('ftpError','echec de l\'envoi du fichier de paramétrage');
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
     * @Route("/loggedin/admin/tpe/edit/{id}", name="editTpe", defaults={"id" = "0"})
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
        $param = $em->getRepository('CibCoreBundle:Parameters')->find(1);
        $ftp = new Ftp($param->getFtpUrl(),$param->getFtpUser(),$param->getFtpPassword(),$param->getFtpPort(),false,false);
        $tpe = $repo->find($id);

        $form = $this->createForm(new TpeType(array('store' => $tpe->getStore(),'tpeParameters' => $tpe->getTpeParameters())),$tpe);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $this->get('session')->getFlashBag()->all();
                $tpe = $form->getData();
                $tpe->getTpeParameters()->createParameterFile($tpe);
                if($tpe->uploadParameterFile($ftp,$request))
                    $this->get('session')->getFlashBag()->add('ftpSuccess','envoi du fichier de paramétrage réussi');
                else
                    $this->get('session')->getFlashBag()->add('ftpError','echec de l\'envoi du fichier de paramétrage');
                $em->persist($tpe);
                $em->flush();


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
     * @Route("/loggedin/admin/tpe/delete/{id}/{token}", name="deleteTpe", defaults={"id" = 0, "token" = 0})
     */
    public function deleteTpeAction(Request $request, $id, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibActivityBundle:Tpe');
        $csrf = $this->get('form.csrf_provider');
        if($id == 0)
        {
            if($request->isMethod('post'))
            {
                if($request->get('multiDelete'))
                {
                    $this->get('session')->getFlashBag()->all();
                    foreach($request->get('multiDelete') as $tpeId)
                    {
                        $tempTpe = $repo->find($tpeId);
                        if(!$tempTpe->rmdir_recursive())
                            $this->get('session')->getFlashBag()->add('error','Echec suppression du fichier de paramétrage');
                        $em->remove($tempTpe);
                    }
                    $em->flush();

                    $this->get('session')->getFlashBag()->add('status','Suppression effectuée');
                }

                return $this->redirect($this->generateUrl('displayStore'));
            }
        }
        else
        {
            $tpe = $repo->find($id);
            if($token == $csrf->generateCsrfToken($tpe->getTpeId()))
            {
                $tpe->rmdir_recursive();
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


} 