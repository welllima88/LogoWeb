<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 14:50
 */

namespace Cib\Bundle\CustomerBundle\Controller;


use Cib\Bundle\CustomerBundle\Entity\bankAccount;
use Cib\Bundle\CustomerBundle\Entity\Card;
use Cib\Bundle\CustomerBundle\Entity\Client;
use Cib\Bundle\CustomerBundle\Entity\Logo;
use Cib\Bundle\CustomerBundle\Form\CardType;
use Cib\Bundle\CustomerBundle\Form\ClientType;
use Cib\Bundle\CustomerBundle\Form\LogoType;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\DBALException;
use Cib\Bundle\CustomerBundle\Opposition\Opposition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;



class CustomerController extends Controller
{

    /**
     * @param Request $request
     * @param $page
     * @param $search
     * @internal param $search
     * @return array
     *
     * @Route("/loggedin/client/display/{page}/{search}", name="displayClient", defaults={ "page"  = 1,"search"= null})
     * @Template()
     */
    public function displayClientAction(Request $request, $page,$search)
    {
//        var_dump($page);die;
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Client');
        if(!$request->request->get('search') && !$search)
            $clients = $repo->findAll();
        else{
            if($request->request->get('txtSearch'))
                $search = $request->request->get('txtSearch');
            $clients = $repo->selectClientList($em,$search);
        }

        $csrf = $this->get('form.csrf_provider');
        foreach($clients as $client)
            $client->setToken($csrf->generateCsrfToken($client->getClientId()));


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $clients,
            $page,
            10
        );

        return[
            'pagination' => $pagination,
            'search' => $search,
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

//        if(!$client->getBankAccount() || !$client->getBankAccount()->getCreditorName())
//            $client->setBankAccount(new bankAccount());

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
    //            var_dump($bankAccount);die;
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
        $bankAccount = new bankAccount();
        $client->setBankAccount($bankAccount);
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
//        var_dump($form->createView()->children);die;
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
     * @param $page
     * @return array
     * @Route("/loggedin/logo/display/{page}", name="displayLogo", defaults={"page" = 1})
     *
     * @Template()
     */
    public function displayLogoAction(Request $request, $page)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Logo');
        $logos = $repo->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $logos,
            $page,
            10
        );

        return [
            'logos' => $logos,
            'pagination' => $pagination,
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
     * @Route("/loggedin/logo/add", name="addLogo")
     *
     * @Template()
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function addLogoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $logo = new Logo();
        $form = $this->createForm(new LogoType(), $logo);

        if($request ->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $logo->upload();
                $em->persist($logo);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Yes');
                $this->showSizeLogoPicture($logo);
                $this->writeFileParam($logo);
                return $this->redirect($this->generateUrl('displayLogo'));

            }

        }

        return[
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/admin/logo/delete/{id}", name="deleteLogo", defaults={"id" = 0})
     */
    public function deleteLogoAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('CibCustomerBundle:Logo');

        $csrf = $this->get('form.csrf_provider');
        if($id == 0)
        {
            if($request->isMethod('POST'))
            {
                if($request->get('multiDelete'))
                {
                    foreach($request->get('multiDelete') as $logoId)
                    {
                        $tempLogo = $repo->find($logoId);
                        try{
                            $em->remove($tempLogo);
                        }
                        catch(DBALException $e)
                        {
                            $this->get('session')->getFlashBag()->all();
                            $this->get('session')->getFlashBag()->add('status','Impossible de supprimer le logo');
                            return $this->redirect($this->generateUrl('displayLogo'));
                        }

                    }
                    $em->flush();
                    $this->get('session')->getFlashBag()->all();
                    $this->get('session')->getFlashBag()->add('status','Suppression effectuée');
                }

                return $this->redirect($this->generateUrl('displayLogo'));
            }
        }
        else
        {
            $logo = $repo->find($id);
            if($csrf->generateCsrfToken($logo->getLogoId()))
            {
                $em->remove($logo);
                $em->flush();
                $this->get('session')->getFlashBag()->all();
                $this->get('session')->getFlashBag()->add('status','Suppression(s) effectuée(s)');
                return $this->redirect($this->generateUrl('displayLogo'));
            }
            else
                throw $this->createNotFoundException('Page introuvable');
        }

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
        $rows = explode("\n",$file);
        foreach($rows as $row)
        {
            $field = explode(";",$row);
            if($field[0] != "")
                $repoClient->setClientFromCsv($em,$field);
        }

        fclose($handle);

        return $this->redirect($this->generateUrl('displayClient'));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/loggedin/print/pdf/{id}", name="printPdf" )
     */
    public function printPdfAction(Request $request, $id)
    {
        $pictureSepa = __DIR__.'/../../../../../web/bundles/cibcore/pictures/logoSepa.jpg';
//        $pictureEga = __DIR__.'/../../../../../web/bundles/cibcore/pictures/pictureEga.jpg';

        $bankAccount = $this->getDoctrine()->getManager()->getRepository('CibCustomerBundle:bankAccount')->find($id);
        $fileName = $bankAccount->getClient()->getClientName().'_'.$bankAccount->getClient()->getClientFirstName();
        $html = $this->renderView('CibCustomerBundle:Customer:writeSepaPdf.html.twig',array('pictureSepa' => $pictureSepa,/*'pictureEga' => $pictureEga,*/'bankAccount' => $bankAccount));
        $html2pdf = new \Html2Pdf_Html2Pdf('L','A4','fr');
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($html);
        $file = $html2pdf->Output('SEPA_'.$fileName.'.pdf','I');
//        $response = new Response();
//        $response->clearHttpHeaders();
//        $response->setContent(file_get_contents($file));
//        $response->headers->set('Content-Type', 'application/force-download');
//        $response->headers->set('Content-disposition', 'filename='. $file);
        die;
        $response = new Response();
        $response->headers->set('Content-Type','application/pdf');
        $response->headers->set('Content-Disposition', "attachment; filename=filename.pdf");
        $response->headers->set('Content-Length', filesize($file));
//        $response->send();
        $response->setContent(file_get_contents($file));

        return $response;
//        ->redirect($this->generateUrl('displayDetailClient',array('id' => $bankAccount->getClient()->getClientId())));

//        $response = new Response();
//        $response->setContent(file_get_contents());
//        $response->headers->set('Content-Type', 'application/force-download');
//        return $response;
//        $response->setContent(file_get_contents($fichier));
//        $response->headers->set('Content-Type', 'application/force-download');
//        $response->headers->set('Content-disposition', 'filename='. $fichier);
//
//        return $response;
    }



    /**
     * @param Request $request
     * @param $id
     * @param $token
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("loggedin/admin/toggle/active/card/{id}/{token}/{active}", name="toggleActiveCard")
     */
    public function lockCardAction(Request $request,$id,$token,$active)
    {
        $csrf = $this->get('form.csrf_provider');
        if($csrf->generateCsrfToken($id) != $token)
            throw $this->createNotFoundException('Page introuvable');

        $em = $this->getDoctrine()->getManager();
        $repoParameters = $em->getRepository('CibCoreBundle:Parameters');
        $parameters = $repoParameters->find(1);
        $ftp = new Ftp($parameters->getFtpUrl(),$parameters->getFtpUser(),$parameters->getFtpPassword(),$parameters->getFtpPort(),false,false);
        $em = $this->getDoctrine()->getManager();
        $repoCard = $em->getRepository('CibCustomerBundle:Card');
        $card = $repoCard->find($id);
        if($active == 0)
            $card->setIsActive(false);
        else if($active ==1)
            $card->setIsActive(true);
        $em->persist($card);
        $em->flush();

        $cards = $repoCard->findBy(array('isActive' => false));
        $opposition = new Opposition($ftp);

        $opposition->writeOppositionFile($cards);
        return $this->redirect($this->generateUrl('displayCard'));
    }





//    /**
//     * @param Request $request
//     * @param $id
//     * @param $token
//     * @return \Symfony\Component\HttpFoundation\RedirectResponse
//     *
//     * @Route("loggedin/admin/unlock/card/{id}/{token}", name="unlockCard")
//     */
//    public function unlockCardAction(Request $request,$id,$token)
//    {
//
//        $csrf = $this->get('form.csrf_provider');
//        if($csrf->generateCsrfToken($id) != $token)
//            throw $this->createNotFoundException('Page introuvable');
//
//        $em = $this->getDoctrine()->getManager();
//        $repoParameters = $em->getRepository('CibCoreBundle:Parameters');
//        $parameters = $repoParameters->find(1);
//        $ftp = new Ftp($parameters->getFtpUrl(),$parameters->getFtpUser(),$parameters->getFtpPassword(),$parameters->getFtpPort(),false,false);
//        $em = $this->getDoctrine()->getManager();
//        $repoCard = $em->getRepository('CibCustomerBundle:Card');
//        $card = $repoCard->find($id);
//        $card->setIsActive(true);
//        $em->persist($card);
//        $em->flush();
//
//
//        $cards = $repoCard->findBy(array('isActive' => false));
//        $opposition = new Opposition($ftp);
//
//        $opposition->writeOppositionFile($cards);
//        return $this->redirect($this->generateUrl('displayCard'));
//    }
    public function showSizeLogoPicture(Logo $logo)
    {
        if ($logo->getLogoTypeTPE() == "ICT250") {

            $this->get('image.handling')->open($logo->getWebPathTop())
                ->resize(200, 200)
                ->save($logo->getPathSrc() . '/ImageTop.png');
            $this->get('image.handling')->open($logo->getWebPathWallpaper())
                ->resize(300, 300)
                ->save($logo->getPathSrc() . '/ImageWallpaper.png');
        }
        else if($logo->getLogoTypeTPE() == "IWL250") {
            $this->get('image.handling')->open($logo->getWebPathTop())
                ->resize(200, 200)
                ->save($logo->getPathSrc() . '/ImageTop.png');
            $this->get('image.handling')->open($logo->getWebPathWallpaper())
                ->resize(300, 300)
                ->save($logo->getPathSrc() . '/ImageWallpaper.png');
        }
        else if ($logo->getLogoTypeTPE() == "EFT930"){
            $this->get('image.handling')->open($logo->getWebPathTop())
                ->resize(200, 200)
                ->save($logo->getPathSrc() . '/ImageTop.png');
            $this->get('image.handling')->open($logo->getWebPathWallpaper())
                ->resize(300, 300)
                ->save($logo->getPathSrc() . '/ImageWallpaper.png');
        }
        else{
            $this->get('image.handling')->open($logo->getWebPathTop())
                ->resize(200, 200)
                ->save($logo->getPathSrc() . '/ImageTop.png');
            $this->get('image.handling')->open($logo->getWebPathWallpaper())
                ->resize(300, 300)
                ->save($logo->getPathSrc() . '/ImageWallpaper.png');
        }
        unlink($logo->getWebPathWallpaper());
        unlink($logo->getWebPathTop());

    }
}