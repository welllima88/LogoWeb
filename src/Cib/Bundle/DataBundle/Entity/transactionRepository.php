<?php

namespace Cib\Bundle\DataBundle\Entity;

use Cib\Bundle\ActivityBundle\Entity\Store;
use Cib\Bundle\ActivityBundle\Entity\Tpe;
use Cib\Bundle\CustomerBundle\Entity\Card;
use Cib\Bundle\CustomerBundle\Entity\Client;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * transactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class transactionRepository extends EntityRepository
{

    public function getAjaxTransactions(EntityManager $em,Card $card = null,Client $client = null,$dateStart = null, $dateStop = null,Store $store = null,$month = null)
    {
        $where = null;
//        if($card != '' || $client != '' || $dateStart || $dateStop || $store != '' || $month!= '' )
//            $where = "WHERE";

        $dql = "SELECT t FROM CibDataBundle:Transaction t";/*WHERE t.card = :card AND t.client = :client AND t.dateTransaction BETWEEN :dateStart AND :dateStop AND t.store = :store AND t.dateTransaction LIKE :month";*/

//        $q = $this->createQueryBuilder('t')
//            ->select('t');
//            if($card != '')
//                $q->add('where','t.card = :card');
//            if($client != '')
//                $q->add('andWhere','t.client = :client');
//            if($dateStart && !$dateStop)
//                $q->add('andWhere','t.dateTransaction >= :dateStart');
//            elseif($dateStop && !$dateStart)
//                $q->add('andWhere','t.dateTransaction >= :dateStop');
//            elseif($dateStart && $dateStop)
//                $q->add('andWhere','t.dateTransaction BETWEEN :dateStart AND :dateStop');
//            if($store)
//                $q->add('andWhere','t.store = :store');
//            if($month)
//                $q->add('andWhere','t.dateTransaction LIKE :month');
//            if($card != '')
//                $q->setParameter('card',$card->getCardId());
//            if($client !='')
//                $q->setParameter('client',$client->getClientId());
//            if($dateStart)
//                $q->setParameter('dateStart',$dateStart->format('Y-m-d'));
//            if($dateStop)
//                $q->setParameter('dateStop',$dateStop->format('Y-m-d'));
//            if($month)
//                $q->setParameter('month',date('Y').$month.'%');
//            if($store)
//                $q->setParameter('store',$store->getStoreId());
//
//            $results = $q->getQuery()
//        ;

        if($where)
        {
            $dql = $dql.$where;
            if($card != '')
                $dql = $dql." t.card = ".$card->getCardId();
            if($client != '')
            {
                if($card != '')
                    $dql = $dql." AND";
                $dql = $dql." t.client = ".$client->getClientId();
            }
            if($dateStart && !$dateStop)
            {
                if($card || $client)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction >= ".$dateStart->format('Y-m-d');
            }
            elseif($dateStop && !$dateStart)
            {
                if($card || $client)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction >= ".$dateStop->format('Y-m-d');
            }
            elseif($dateStart && $dateStop)
            {
                if($card || $client)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction BETWEEN ".$dateStart->format('Y-m-d')." AND ".$dateStop->format('Y-m-d');
            }
            if($store)
            {
                if($card || $client || $dateStop || $dateStart)
                    $dql = $dql." AND";
                $dql = $dql." t.store = ".$store->getStoreId();
            }
            if($month)
            {
                $month = date('Y').$month.'%';
                if($card || $client || $dateStop || $dateStart || $store)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction LIKE ".$month;
            }


        }
        $query = $em->createQuery($dql);
//        $query->getResult();
//        $query->setParameters(array(
//            'card' => $card->getCardId(),
//            'client' => $client->getClientId(),
//            'dateStart' => $dateStart->format('Y-m-d'),
//            'dateStop' => $dateStop->format('Y-m-d'),
//            'store' => $store->getStoreId(),
//            'month' => $month,
//        ));

//        $results = $q->getQuery;
//        var_dump($results->getArrayResult());
//        $serializer = new Serializer(array(new GetSetMethodNormalizer()),array('json' => new JsonEncoder()));
//        $transactions = new ArrayCollection();
//        foreach($query->getResult() as $result)
//        {
////            var_dump($result);
////            $transactions->add(new Transaction($result['dateTransaction'],$result['typeTransaction'],$result['pmeTransaction'],$result['amountTransaction'],$result['primeTransaction'],$result['card']['cardNumber'],$result['tpe']['tpeNumber'],$result['isVipTransaction'],$em));
//            $json = $serializer->serialize(new Transaction($result->getDateTransaction()->format('Y-m-d'),$result->getTypeTransaction(),$result->getPmeTransaction(),$result->getAmountTransaction(),$result->getPrimeTransaction(),$result->getCard()->getCardNumber(),$result->getTpe()->getTpeNumber(),$result->getIsVipTransaction(),$em),'json');
////            $transactions->add(new Transaction($result->getDateTransaction()->format('Y-m-d'),$result->getTypeTransaction(),$result->getPmeTransaction(),$result->getAmountTransaction(),$result->getPrimeTransaction(),$result->getCard()->getCardNumber(),$result->getTpe()->getTpeNumber(),$result->getIsVipTransaction(),$em));
//    $transactions->add($json);
//        }
//        var_dump($transactions);
//        foreach($results as $result)
//        {
//            $transaction = new Transaction($result->getDateTransaction(),$result->getTypeTransaction(),$result->getPmeTransaction(),$result->getAmountTransaction(),$result->getPrimeTransaction(),$result->getCard()->getCardNumber(),$result->getTpe()->getTpeNumber(),$result->getisVipTransaction(),$em);
////            $array = array(
////                'amountTransaction' => $result->getAmountTransaction(),
////                'dateTransaction' => $result->getDateTransaction(),
////                'isVipTransaction' => $result->getIsVipTransaction(),
////                'pmeTransaction' => $result->getPmeTransaction(),
////                'primeTransaction' => $result->getPrimeTransaction(),
////                'transactionId' => $result->getTransactionId(),
////                'typeTransaction' => $result->getTypeTransaction(),
////                'cardNumber' => $result->getCard()->getCardNumber(),
////                'tpeNumber' => $result->getTpe()->getTpeNumber(),
////                'storeNumber' => $result->getStore()->getStoreNumber(),
////                'clientName' => $result->getClient()->getClientName(),
////            );
//            $transactions->add($transaction);
//        }
//        var_dump($query->getResult());

        return $query->getResult();
    }
}
