<?php

namespace Cib\Bundle\CustomerBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * cardRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class cardRepository extends EntityRepository
{

    public function selectCardList(EntityManager $em,$search)
    {
        $dql = "SELECT c FROM CibCustomerBundle:Card c WHERE c.cardNumber LIKE :search ";
        $query = $em->createQuery($dql);
        $query->setParameter('search','%'.$search.'%');

        return $cards = $query->getResult();
    }

    public function selectAjaxCardList(EntityManager $em,$search)
    {
        $dql = "SELECT c FROM CibCustomerBundle:Card c WHERE c.cardNumber LIKE :search ";
        $query = $em->createQuery($dql);
        $query->setParameter('search','%'.$search.'%');

        $cards = $query->getArrayResult();

        return new Response(json_encode($cards), 200);
    }

    public function selectAjaxCard(EntityManager $em,$search)
    {
        $dql = "SELECT c FROM CibCustomerBundle:Card c WHERE c.cardNumber LIKE :search ";
        $query = $em->createQuery($dql);
        $query->setParameter('search',$search);

        return $query->getOneOrNullResult();
    }
}
