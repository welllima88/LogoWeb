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
        if($card != '' || $client != '' || $dateStart != '' || $dateStop!= '' || $store != '' || $month!= '' )
            $where = "WHERE";

        $dql = "SELECT t FROM CibDataBundle:Transaction t ";
        $dateNow = new \DateTime();
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
            if($dateStart && $dateStop == '')
            {
                if($card || $client)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction BETWEEN '".$dateStart->format('Y-m-d')."' AND '".$dateNow->format('Y-m-d')."'";
            }
            elseif($dateStop && $dateStart == '')
            {
                if($card || $client)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction <= '".$dateStop->format('Y-m-d')."'";
            }
            elseif($dateStart != '' && $dateStop != '')
            {
                if($card || $client)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction BETWEEN '".$dateStart->format('Y-m-d')."' AND '".$dateStop->format('Y-m-d')."'";
            }
            if($store)
            {
                if($card || $client || $dateStop || $dateStart)
                    $dql = $dql." AND";
                $dql = $dql." t.store = ".$store->getStoreId();
            }
            if($month)
            {
                $month = date('Y').'-'.$month.'%';
                if($card || $client || $dateStop || $dateStart || $store)
                    $dql = $dql." AND";
                $dql = $dql." t.dateTransaction LIKE '".$month."'";
            }


        }
        $query = $em->createQuery($dql);
        return $query->getResult();
    }
}