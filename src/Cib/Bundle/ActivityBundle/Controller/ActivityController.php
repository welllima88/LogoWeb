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
use Cib\Bundle\CustomerBundle\Entity\Logo;
use Cib\Bundle\DataBundle\Treatment\Treatment;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Proxies\__CG__\Cib\Bundle\ActivityBundle\Entity\Store;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\Null;
use Symfony\Component\Validator\Constraints\True;

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
        $signboards = $this->getDataList($repo, $request->request->get('txtSearch'));

//            $users = $userRepo->findBy(array('user' => $this->get('security.context')->getToken()->getUser()));

//        if(!$request->request->get('search'))
//            $signboards = $repo->findAll();
//        else
//            $signboards = $repo->selectSignboardList($em,$request->request->get('txtSearch'));

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
        $form = $this->createForm(new SignboardType($this->get('security.context')));
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

        $form = $this->createForm(new SignboardType($this->get('security.context')),$signboard);
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
        $stores = $this->getDataList($repo, $request->request->get('txtSearch'));

/*        if(!$request->request->get('search'))
            $stores = $repo->findAll();
        else
            $stores = $repo->selectStoreList($em, $request->request->get('txtSearch'));*/

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
     * @Route("/loggedin/store/add", name="addStore")
     *
     * @Template()
     */
    public function addStoreAction(Request $request)
    {
        $store = new \Cib\Bundle\ActivityBundle\Entity\Store();
        $em = $this->getDoctrine()->getManager();
        $repoSignboard = $em->getRepository('CibActivityBundle:Signboard');
        if ($this->get('security.context')->getToken()->getUser()->isAdmin())
            $signboards = $repoSignboard->findAll();
        else
            $signboards = $repoSignboard->findBy(array('user' => $this->get('security.context')->getToken()->getUser()));

        $form = $this->createForm(new StoreType($signboards),$store);
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
     * @Route("/loggedin/store/delete/{id}/{token}", name="deleteStore", defaults={"id" = 0, "token" = 0})
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
        $tpe = $this->getDataList($repo, $request->request->get('txtSearch'));
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


    public function removeOneRight($tpe)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $storeRepo = $em->getRepository('CibActivityBundle:Store');
        $signboardRepo = $em->getRepository('CibActivityBundle:Signboard');
        $userRepo = $em->getRepository('CibUserBundle:User');

        $store = $storeRepo->find($tpe->getStore());
        $signboard = $signboardRepo->find($store->getSignboard());
        $user = $userRepo->find($signboard->getUser());
        $user->setRights($user->getRights() - 1);
        $em->flush();
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
        $tpe = new Tpe();
        $logo = new Logo();

        $tpe->setLogo($logo);
        $userId = $this->container->get('security.context')->getToken()->getUser()->getId();
        $user = $em->getRepository('CibUserBundle:User')->find($userId);
        $param = $em->getRepository('CibCoreBundle:Parameters')->find(1);
        $ftp = new Ftp($param->getFtpUrl(),$param->getFtpUser(),$param->getFtpPassword(),$param->getFtpPort(),false,false);
        $form = $this->createForm(new TpeType(),$tpe);
        $form->handleRequest($request);

        if($form->isValid())
        {
            $tpe = $form->getData();
            //$tpe->getTpeParameters()->createParameterFile($tpe);
/*            if($tpe->uploadParameterFile($ftp,$request))
                $this->get('session')->getFlashBag()->add('ftpSuccess','envoi du fichier de paramétrage réussi');
            else
                $this->get('session')->getFlashBag()->add('ftpError','echec de l\'envoi du fichier de paramétrage');*/

            if ($user->getRights() <= 0)
            {
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('ftpError', 'Manque de droit pour ajouter un tpe');
                return $this->redirect($this->generateUrl('displayTpe'));
            }
            $em->persist($tpe);
            $em->flush();
            if ($logo) {
                $this->removeOneRight($tpe);
                $this->showSizeLogoPicture($logo, $tpe);
                $logo->writeFileParam($logo, $ftp);
                $this->sendFiletoFtp($logo, $tpe);
            }
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
        $param = $em->getRepository('CibCoreBundle:Parameters')->find(1);
        $ftp = new Ftp($param->getFtpUrl(),$param->getFtpUser(),$param->getFtpPassword(),$param->getFtpPort(),false,false);
        $tpe = $repo->find($id);
        $form = $this->createForm(new TpeType(array('logo' => $tpe->getLogo(), 'store' => $tpe->getStore(),'tpeParameters' => $tpe->getTpeParameters())),$tpe);
        $form->handleRequest($request);

        if($form->isValid())
        {
            if($request->request->get('valider'))
            {
                $this->get('session')->getFlashBag()->all();
                $tpe = $form->getData();
                $tpe->getTpeParameters()->createParameterFile($tpe);
                if($tpe->uploadParameterFile($ftp))
                    $this->get('session')->getFlashBag()->add('ftpSuccess','envoi du fichier de paramétrage réussi');
                else
                    $this->get('session')->getFlashBag()->add('ftpError','echec de l\'envoi du fichier de paramétrage');
                $em->persist($tpe);
                $em->flush();
                if ($logo) {
                    $this->showSizeLogoPicture($logo, $tpe);
                    $logo->writeFileParam($logo, $ftp);
                    $this->sendFiletoFtp($logo, $tpe);
                }

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
     * @Route("/loggedin/tpe/delete/{id}/{token}", name="deleteTpe", defaults={"id" = 0, "token" = 0})
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



    protected function getTreatmentDirList()
    {
        $em = $this->getDoctrine()->getManager();
        $repoTpe = $em->getRepository('CibActivityBundle:Tpe');

        $doneDir = @scandir('done');
        $failDir = @scandir('fail');
        $array['done'] = new ArrayCollection();
        $array['fail'] = new ArrayCollection();
        foreach($doneDir as $done){
            if($done != '.' && $done != '..')
                $array['done']->add($repoTpe->findOneBy(array('tpeNumber' => $done)));
        }
        foreach($failDir as $fail)
        {
            if($fail != '.' && $fail != '..')
                $array['fail']->add($repoTpe->findOneBy(array('tpeNumber' => $done)));
        }

        return $array;
    }

    private function getDataList($repository, $search = null)
    {
        if($this->get('security.context')->getToken()->getUser()->isAdmin()){
            if(!$search)
               return  $repository->findAll();
            else
                return $repository->selectList($search);
        }
        else{
            if(!$search)
                return $repository->getByUser($this->get('security.context')->getToken()->getUser());
            else
                return  $repository->getByUser($this->get('security.context')->getToken()->getUser(),$search);

        }
    }

    public function sizeRenamePictureNonGoalLogo(Logo $logo)
    {
        if ($logo->getLogoTypeTPE() == "ICT250") {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.bmp');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.bmp');
            }
        }
        else if($logo->getLogoTypeTPE() == "IWL250") {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.bmp');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.bmp');
            }
        }
        else if ($logo->getLogoTypeTPE() == "EFT930") {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.bmp');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.bmp');
            }
        }
        else {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.bmp');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.bmp');
            }
        }
    }

    public function sizeRenamePictureGoalLogo(Logo $logo)
    {
        if ($logo->getLogoTypeTPE() == "ICT250") {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.jpg');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                if ($logo->getWebPathWallpaper() != Null) {
                    $this->get('image.handling')->open($logo->getWebPathWallpaper())
                        ->resize(300, 300)
                        ->save($logo->getPathSrc() . '/ImageWallpaper.jpg');
                }
            }
        }
        else if($logo->getLogoTypeTPE() == "IWL250") {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.jpg');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.jpg');
            }
        }
        else if ($logo->getLogoTypeTPE() == "EFT930") {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.jpg');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.jpg');
            }
        }
        else {
            if ($logo->getWebPathTop() != Null) {
                $this->get('image.handling')->open($logo->getWebPathTop())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageTop.jpg');
            }
            if ($logo->getWebPathWallpaper() != Null) {
                $this->get('image.handling')->open($logo->getWebPathWallpaper())
                    ->resize(300, 300)
                    ->save($logo->getPathSrc() . '/ImageWallpaper.jpg');
            }
        }
    }

    public function sendFiletoFtp(Logo $logo , Tpe $tpe)
    {
        $iPort = 21200;

        $ftp_flux = ftp_connect("92.222.171.6", $iPort);
        if (ftp_login($ftp_flux, "logo", "logocib"))
        {
            if (preg_match("/Windows/i", $_SERVER["HTTP_USER_AGENT"]) == 1)
                ftp_pasv($ftp_flux, true);

            ftp_mkdir($ftp_flux, $tpe->getTpeNumber());

            if (file_exists($logo->getPathSrc().'/ImageTop.jpg'))
            {
                ftp_put($ftp_flux, $tpe->getTpeNumber() . '/ImageTop.jpg', $logo->getPathSrc(). '/ImageTop.jpg',FTP_ASCII);
            }
            if (file_exists($logo->getPathSrc().'/ImageTop.bmp'))
                ftp_put($ftp_flux, $tpe->getTpeNumber(). '/ImageTop.bmp', $logo->getPathSrc(). '/ImageTop.bmp', FTP_ASCII);
            if (file_exists($logo->getPathSrc().'/ImageWallpaper.jpg'))
                ftp_put($ftp_flux, $tpe->getTpeNumber(). '/ImageWallpaper.jpg', $logo->getPathSrc(). '/ImageWallpaper.jpg', FTP_ASCII);
            if (file_exists($logo->getPathSrc().'/ImageWallpaper.bmp'))
                ftp_put($ftp_flux, $tpe->getTpeNumber().'/ImageWallpaper.bmp', $logo->getPathSrc(). '/ImageWallpaper.bmp', FTP_ASCII);
            if (file_exists($logo->getPathSrc(). '/PARAM_LOGO.PAR'))
                ftp_put($ftp_flux, $tpe->getTpeNumber().'/PARAM_LOGO.PAR', $logo->getPathSrc(). '/PARAM_LOGO.PAR', FTP_ASCII);
        }

        ftp_close($ftp_flux);
    }

    public function showSizeLogoPicture(Logo $logo, Tpe $tpe)
    {
        if ($logo->getLogoGoal() == "true") {
            $this->sizeRenamePictureGoalLogo($logo);
        } else
            $this->sizeRenamePictureNonGoalLogo($logo);


        if ($logo->getWebPathWallpaper() != Null) {
            unlink($logo->getWebPathWallpaper());
        }
        if ($logo->getWebPathTop() != Null) {
            unlink($logo->getWebPathTop());
        }
    }


} 