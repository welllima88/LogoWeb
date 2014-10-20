<?php

namespace Cib\Bundle\DataBundle\Controller;

use Cib\Bundle\DataBundle\Entity\Transaction;
use Cib\Bundle\DataBundle\Form\ResultsType;
use Cib\Bundle\DataBundle\Treatment\Treatment;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Cib\Bundle\CustomerBundle\Entity\cardRepository;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use JMS\Serializer\SerializerBuilder;

class DataController extends Controller
{
    /**
     * @Route("loggedin/results/display/{page}", name="displayResults", defaults={"page" = 1})
     *
     * @Template()
     * @param Request $request
     * @param $page
     * @return array|\Symfony\Component\BrowserKit\Response
     */
    public function displayResultsAction(Request $request,$page)
    {
        $em = $this->getDoctrine()->getManager();
        $repoTransaction = $em->getRepository('CibDataBundle:Transaction');
        $repoResults = $em->getRepository('CibDataBundle:Results');
        $repoCard = $em->getRepository('CibCustomerBundle:Card');
        $repoClient = $em->getRepository('CibCustomerBundle:Client');
        $repoStore = $em->getRepository('CibActivityBundle:Store');
        $formResult = $this->createForm(new ResultsType());
        $serializer = new SerializerBuilder();
        if($request->isXmlHttpRequest())
        {
            if($request->request->get('start'))
                $dateStart = new \DateTime($request->request->get('start'));
            else
                $dateStart = '';
            if($request->request->get('stop'))
                $dateStop = new \DateTime($request->request->get('stop'));
            else
                $dateStop = '';
            if($request->request->get('searchCard'))
                return($repoCard->selectAjaxCardList($em,$request->request->get('searchCard')));
            if($request->request->get('searchClient'))
                return($repoClient->selectAjaxClientList($em,$request->request->get('searchClient')));
            if($request->request->get('saveResult'))
                return($repoResults->saveResultChoices($request->request->get('month'),$dateStart,$dateStop,$repoCard->selectAjaxCard($em,$request->request->get('card')),$repoClient->selectAjaxClient($em,$request->request->get('client')),$repoStore->selectAjaxStore($em,$request->request->get('store')),$request->request->get('name'),$em));
            if($request->request->get('resultList'))
                return new Response($serializer->create()->build()->serialize($repoResults->findAll(),'json'),200);
            if($request->request->get('deleteResult'))
                return $repoResults->deleteResultAjax($request->request->get('id'),$em);
            if($request->request->get('result'))
            {
                $results = $repoTransaction->getAjaxTransactions($em,$repoCard->selectAjaxCard($em,$request->request->get('card')),$repoClient->selectAjaxClient($em,$request->request->get('client')),$dateStart,$dateStop,$repoStore->selectAjaxStore($em,$request->request->get('store')),$request->request->get('month'));
                return new Response($serializer->create()->build()->serialize($results,'json'),200);
            }

        }

//        $paginator= $this->get('knp_paginator');
//        $pagination = $paginator->paginate(
//            $results,
//            $page,
//            20
//        );


        return[
            'pagination' => 'pagination',
            'form' => $formResult->createView(),
            'results' => 'results',
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("loggedin/results/treat/files", name="treatFiles")
     */
    public function treatResultsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoParameters = $em->getRepository('CibCoreBundle:Parameters');
        $parameters = $repoParameters->find(1);
        $ftp = new Ftp($parameters->getFtpUrl(),$parameters->getFtpUser(),$parameters->getFtpPassword(),$parameters->getFtpPort(),false,false);
        $treatment = new Treatment($this->getDoctrine()->getManager());
        $treatment->downloadDataFile($ftp);
        $transactions = $treatment->treatDataFiles();
//        foreach($transactions as $transaction)
//        {
//            $em->persist($transaction);
//            $em->flush();
//        }
        return $this->redirect($this->generateUrl('displayResults'));
    }
}
