<?php

namespace Cib\Bundle\DataBundle\Controller;

use Cib\Bundle\DataBundle\Entity\Transaction;
use Cib\Bundle\DataBundle\Form\EncloseType;
use Cib\Bundle\DataBundle\Form\ResultsType;
use Cib\Bundle\DataBundle\Treatment\Treatment;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use PHPExcel_Style_Border;
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
        $totalDebit = 0;
        $totalPrime = 0;
        $totalVip = 0;
        $totalCredit = 0;
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
                if($request->request->get('page'))
                    $page = $request->request->get('page');
                $results = $repoTransaction->getAjaxTransactions($em,$repoCard->selectAjaxCard($em,$request->request->get('card')),$repoClient->selectAjaxClient($em,$request->request->get('client')),$dateStart,$dateStop,$repoStore->selectAjaxStore($em,$request->request->get('store')),$request->request->get('month'));

                foreach($results as $result)
                {
                    if($result->getTypeTransaction() == 'D')
                        $totalDebit += $result->getAmountTransaction();
                    if($result->getTypeTransaction() == 'C')
                    {
                        $totalPrime += $result->getPrimeTransaction();
                        if($result->getIsVipTransaction())
                            $totalVip += $result->getAmountTransaction();
                        else
                            $totalCredit += $result->getAmountTransaction();
                    }
                }
                $array = array(
                    'totalDebit' => $totalDebit,
                    'totalCredit' => $totalCredit,
                    'totalPrime' => $totalPrime,
                    'totalVip' => $totalVip,
                    'card' => $repoCard->selectAjaxCard($em,$request->request->get('card')),
                    'client' => $repoClient->selectAjaxClient($em,$request->request->get('client')),
                    'start' => $dateStart,
                    'stop' => $dateStop,
                    'store' => $repoStore->selectAjaxStore($em,$request->request->get('store')),
                    'month' => $request->request->get('month')
                );
                $paginator= $this->get('knp_paginator');
                $pagination = $paginator->paginate(
                    $results,
                    $page,
                    20
                );
                $arrayResult['total'] = $array;
                $arrayResult['pagination'] = $pagination;
                return new Response($serializer->create()->build()->serialize($arrayResult,'json'),200);
            }

        }


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
     * @Route("results/treat/files", name="treatFiles")
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

        return $this->redirect($this->generateUrl('displayResults'));
    }

    /**
     * @param Request $request
     *
     * @return array
     * @Route("/loggedin/display/result/compensation", name="displayCompensation")
     * @Template()
     */
    public function displayCompensationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoTransactions = $em->getRepository('CibDataBundle:Transaction');
        $repoStore = $em->getRepository('CibActivityBundle:Store');
        $repoEnclose = $em->getRepository('CibDataBundle:Enclose');

        $formEnclose = $this->createForm(new EncloseType($this->getStores($repoStore->findAll())));
        $serializer = new SerializerBuilder();
        if($request->isXmlHttpRequest())
        {
            if($request->request->get('arrayStore'))
            {
                $arrayStore = $request->request->get('arrayStore');
                $result = $repoTransactions->getAjaxEnclose($em,$arrayStore,$request->request->get('date'));
                return new Response($serializer->create()->build()->serialize($result,'json'));
            }
            if($request->request->get('enclose'))
            {
                if($request->request->get('enclose') == 'singleEnclose')
                {
                    $result = $repoTransactions->encloseOneStore($em,$request->request->get('store'),$request->request->get('debit'),$request->request->get('credit'),$request->request->get('vip'),$request->request->get('prime'),$request->request->get('balance'),$request->request->get('historic'),$request->request->get('real'),$request->request->get('dateStart'),$request->request->get('dateStop'));
                    if($result == true)
                        return new Response('success',200);
                    else
                        return new Response('error',500);
                }
            }

        }


        return[
            'compensation' => 'compensation',
            'form' => $formEnclose->createView(),
        ];
    }


    protected function getStores(array $stores) {
        $storeArray = array();

        foreach ($stores as $test) {
            $storeArray[$test->getStoreId()] = $test->getStoreName();
        }
        return $storeArray;
    }

    /**
     * @param Request $request
     *
     * @Route("/loggedin/export/data/results/{month}/{start}/{stop}/{numCard}/{client}/{store}", name="exportDataResults", defaults={"month" = null,"start" = null,"stop" = null,"numCard" = null,"client" = null,"store" = null})
     */
    public function exportTransactionsResultsAction(Request $request,$month,$start,$stop,$numCard,$client,$store)
    {

        if($start)
            $dateStart = new \DateTime($start);
        else
            $dateStart = '';
        if($stop)
            $dateStop = new \DateTime($stop);
        else
            $dateStop = '';

        $em = $this->getDoctrine()->getManager();
        $repoTransaction = $em->getRepository('CibDataBundle:Transaction');
        $repoCard = $em->getRepository('CibCustomerBundle:Card');
        $repoClient = $em->getRepository('CibCustomerBundle:Client');
        $repoStore = $em->getRepository('CibActivityBundle:Store');
        $results = $repoTransaction->getAjaxTransactions($em,$repoCard->selectAjaxCard($em,$numCard),$repoClient->selectAjaxClient($em,$client),$dateStart,$dateStop,$repoStore->selectAjaxStore($em,$store),$month);

        return($this->createExcelTransactions($results));
    }


    /**
     * @param Request $request
     * @param $store
     * @param $date
     *
     * @Route("/loggedin/export/enclose/results/{store}/{date}", defaults={"store"=0,"date"=0}, name="exportEncloseResults")
     */
    public function exportEncloseResultsAction(Request $request,$store,$date)
    {
        if($date)
            $date = new \DateTime($date);

        $em = $this->getDoctrine()->getManager();
        $arrayStore = explode(";",$store);
        $stores = null;
        foreach($arrayStore as $store)
        {
            if($store != "")
                $stores[] = $store;
        }

//        var_dump($stores);die;
        $repoTransaction = $em->getRepository('CibDataBundle:Transaction');
        $results = $repoTransaction->getAjaxEnclose($em,$stores,$date);

//        var_dump($results);die;
        return $this->createExcelEnclose($results);

    }

    protected function createExcelEnclose($data)
    {
        $i = 4;
        $date = new \DateTime();
        $timezone = new \DateTimeZone('Europe/Paris');
        $date->setTimezone($timezone);
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("CashPass")
            ->setTitle("Export")
            ->setSubject("Export");

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A3','SITES')
            ->setCellValue('B3','DEBIT')
            ->setCellValue('C3','CREDIT')
            ->setCellValue('D3','VIP')
            ->setCellValue('E3','PRIME')
            ->setCellValue('F3','SOLDES')
            ->setCellValue('G3','HISTORIQUE')
            ->setCellValue('H3','REEL');

//        var_dump($data);die;
        foreach($data as $enclose)
        {
            if($enclose->getLastEnclose())
                $lastEnclose = $enclose->getLastEnclose()->getTotalBalance();
            else
                $lastEnclose = 0;

            if(!$enclose->getTotalDebit())
                $debit = 0;
            else
                $debit = $enclose->getTotalDebit();
            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,$enclose->getStore()->getStoreName())
                ->setCellValue('B'.$i,$debit)
                ->setCellValue('C'.$i,($enclose->getTotalCredit() - $enclose->getTotalPrime()))
                ->setCellValue('D'.$i,$enclose->getTotalVip() - $enclose->getTotalPrime())
                ->setCellValue('E'.$i,$enclose->getTotalPrime())
                ->setCellValue('F'.$i,($enclose->getTotalCredit()+$enclose->getTotalVip())-$enclose->getTotalDebit())
                ->setCellValue('G'.$i,$lastEnclose)
                ->setCellValue('H'.$i,(($enclose->getTotalCredit()+$enclose->getTotalVip())-$enclose->getTotalDebit())+$lastEnclose);

            $this->setRowTableStyle(0,$i,$phpExcelObject);
            $this->setRowTableStyle(1,$i,$phpExcelObject);
            $this->setRowTableStyle(2,$i,$phpExcelObject);
            $this->setRowTableStyle(3,$i,$phpExcelObject);
            $this->setRowTableStyle(4,$i,$phpExcelObject);
            $this->setRowTableStyle(5,$i,$phpExcelObject);
            $this->setRowTableStyle(6,$i,$phpExcelObject);
            $this->setRowTableStyle(7,$i,$phpExcelObject);

            $this->setFormatCurrencyEur(1,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(2,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(3,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(4,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(5,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(6,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(7,$i,$phpExcelObject);
            $i++;
        }

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($i+1),'TOTAL')
            ->setCellValue('B'.($i+1),'DEBIT')
            ->setCellValue('C'.($i+1),'CREDIT')
            ->setCellValue('D'.($i+1),'VIP')
            ->setCellValue('E'.($i+1),'PRIME')
            ->setCellValue('F'.($i+1),'SOLDE')
            ->setCellValue('G'.($i+1),'HISTORIQUE')
            ->setCellValue('H'.($i+1),'REEL');

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($i+2),'')
            ->setCellValue('B'.($i+2),'=SUM(B4:B'.($i-1).')')
            ->setCellValue('C'.($i+2),'=SUM(C4:C'.($i-1).')')
            ->setCellValue('D'.($i+2),'=SUM(D4:D'.($i-1).')')
            ->setCellValue('E'.($i+2),'=SUM(E4:E'.($i-1).')')
            ->setCellValue('F'.($i+2),'=SUM(F4:F'.($i-1).')')
            ->setCellValue('G'.($i+2),'=SUM(G4:G'.($i-1).')')
            ->setCellValue('H'.($i+2),'=SUM(H4:H'.($i-1).')');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
//        $worksheet = ;
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $this->setTitleTableStyle(0,3,$phpExcelObject);
        $this->setTitleTableStyle(1,3,$phpExcelObject);
        $this->setTitleTableStyle(2,3,$phpExcelObject);
        $this->setTitleTableStyle(3,3,$phpExcelObject);
        $this->setTitleTableStyle(4,3,$phpExcelObject);
        $this->setTitleTableStyle(5,3,$phpExcelObject);
        $this->setTitleTableStyle(6,3,$phpExcelObject);
        $this->setTitleTableStyle(7,3,$phpExcelObject);

        $this->setTitleTableStyle(0,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(1,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(2,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(3,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(4,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(5,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(6,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(7,($i+1),$phpExcelObject);

        $this->setRowTableStyle(0,($i+2),$phpExcelObject);
        $this->setRowTableStyle(1,($i+2),$phpExcelObject);
        $this->setRowTableStyle(2,($i+2),$phpExcelObject);
        $this->setRowTableStyle(3,($i+2),$phpExcelObject);
        $this->setRowTableStyle(4,($i+2),$phpExcelObject);
        $this->setRowTableStyle(5,($i+2),$phpExcelObject);
        $this->setRowTableStyle(6,($i+2),$phpExcelObject);
        $this->setRowTableStyle(7,($i+2),$phpExcelObject);

        $this->setFormatCurrencyEur(0,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(1,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(2,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(3,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(4,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(5,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(6,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(7,($i+2),$phpExcelObject);

        $phpExcelObject->getActiveSheet()->setTitle('Compensation');
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=ExportCompensation'.$date->format('Y-m-d h:i:s').'.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');



        return $response;
    }

    protected function createExcelTransactions($data)
    {
        $i = 4;
        $totalDebit = 0;
        $totalVip = 0;
        $totalPrime = 0;
        $totalCredit = 0;
        $balance = 0;
        $date = new \DateTime();
        $timezone = new \DateTimeZone('Europe/Paris');
        $date->setTimezone($timezone);
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->getProperties()->setCreator("CashPass")
            ->setTitle("Export")
            ->setSubject("Export");

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A3', 'DATE')
            ->setCellValue('B3', 'CARTE')
            ->setCellValue('C3', 'VIP')
            ->setCellValue('D3', 'PRIME')
            ->setCellValue('E3', 'DEBIT')
            ->setCellValue('F3', 'CREDIT');

        foreach($data as $transaction)
        {
            if($transaction->getTypeTransaction() == 'D')
                $totalDebit += $transaction->getAmountTransaction();
            if($transaction->getTypeTransaction() == 'C')
            {
                $totalPrime += $transaction->getPrimeTransaction();
                if($transaction->getIsVipTransaction())
                    $totalVip += $transaction->getAmountTransaction();
                else
                    $totalCredit += $transaction->getAmountTransaction();
            }

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$i,$transaction->getDateTransaction()->format('d/m/Y'))
                ->setCellValue('B'.$i,$transaction->getCard()->getCardNumber());

            if($transaction->getIsVipTransaction())
            {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('C'.$i,"OUI");
            }
            else
            {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('C'.$i,"NON");
            }

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('D'.$i,$transaction->getPrimeTransaction());

            if($transaction->getTypeTransaction() == 'D')
            {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('E'.$i,$transaction->getAmountTransaction());
            }
            else
            {

                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('E'.$i,'0');
            }
            if($transaction->getTypeTransaction() == 'C')
            {
                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('F'.$i,$transaction->getAmountTransaction());
            }
            else
            {

                $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('F'.$i,'0');
            }




            $this->setRowTableStyle(0,$i,$phpExcelObject);
            $this->setRowTableStyle(1,$i,$phpExcelObject);
            $this->setRowTableStyle(2,$i,$phpExcelObject);
            $this->setRowTableStyle(3,$i,$phpExcelObject);
            $this->setRowTableStyle(4,$i,$phpExcelObject);
            $this->setRowTableStyle(5,$i,$phpExcelObject);


            $this->setFormatCurrencyEur(3,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(4,$i,$phpExcelObject);
            $this->setFormatCurrencyEur(5,$i,$phpExcelObject);

            $i++;
        }
        $balance = $totalCredit + $totalVip - $totalDebit;
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A'.($i+1),'TOTAL');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('C'.($i+1),'VIP');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('D'.($i+1),'PRIME');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('E'.($i+1),'DEBIT');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('F'.($i+1),'CREDIT');
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('G'.($i+1),'SOLDE');

        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('C'.($i+2),$totalVip);
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('D'.($i+2),$totalPrime);
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('E'.($i+2),$totalDebit);
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('F'.($i+2),$totalCredit);
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('G'.($i+2),$balance);

        $this->setRowTableStyle(0,$i+2,$phpExcelObject);
        $this->setRowTableStyle(1,$i+2,$phpExcelObject);
        $this->setRowTableStyle(2,$i+2,$phpExcelObject);
        $this->setRowTableStyle(3,$i+2,$phpExcelObject);
        $this->setRowTableStyle(4,$i+2,$phpExcelObject);
        $this->setRowTableStyle(5,$i+2,$phpExcelObject);
        $this->setRowTableStyle(6,$i+2,$phpExcelObject);

        $this->setFormatCurrencyEur(2,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(3,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(4,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(5,($i+2),$phpExcelObject);
        $this->setFormatCurrencyEur(6,($i+2),$phpExcelObject);

        $phpExcelObject->getActiveSheet()->setTitle('Transactions');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);
//        $worksheet = ;
        $phpExcelObject->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $phpExcelObject->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

        $phpExcelObject->getActiveSheet()->getRowDimension('3')->setRowHeight(25);

        $this->setTitleTableStyle(0,3,$phpExcelObject);
        $this->setTitleTableStyle(1,3,$phpExcelObject);
        $this->setTitleTableStyle(2,3,$phpExcelObject);
        $this->setTitleTableStyle(3,3,$phpExcelObject);
        $this->setTitleTableStyle(4,3,$phpExcelObject);
        $this->setTitleTableStyle(5,3,$phpExcelObject);

        $this->setTitleTableStyle(0,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(1,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(2,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(3,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(4,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(5,($i+1),$phpExcelObject);
        $this->setTitleTableStyle(6,($i+1),$phpExcelObject);

//        ->getBorderStyle()->setBorderStyle(\PHPExcel_Style_Border::BORDER_DOUBLE);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=ExportTransactions'.$date->format('Y-m-d h:i:s').'.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;

    }

//    protected function createEncloseExcelAction(Request $request,)
    protected function setTitleTableStyle($column,$row,\PHPExcel $phpExcelObject)
    {
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getFont()->setBold(true)
            ->setName('Helvetica Neue')
            ->setSize(18)
            ->getColor()->setRGB('FFFFFF');

        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray(array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '102028')
            )
        ));
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    }

    protected function setRowTableStyle($column,$row,\PHPExcel $phpExcelObject)
    {
        if($row%2)
            $color = '737373';
        else
            $color = 'd5d5d5';

        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->applyFromArray(array(
            'fill' => array(
                'type' => \PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => $color)
            )
        ));
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getFont()->setSize(11)
            ->setName('Helvetica Neue');


    }

    protected function setFormatCurrencyEur($column,$row,\PHPExcel $phpExcelObject)
    {
        $phpExcelObject->getActiveSheet()->getStyleByColumnAndRow($column,$row)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00_EUR);
    }
}
