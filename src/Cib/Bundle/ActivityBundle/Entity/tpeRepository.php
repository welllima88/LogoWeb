<?php

namespace Cib\Bundle\ActivityBundle\Entity;

use Cib\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * tpeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class tpeRepository extends EntityRepository
{

    public function selectList($search)
    {
        $dql = "SELECT t FROM CibActivityBundle:Tpe t WHERE (t.tpeNumber LIKE :search)";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('search','%'.$search.'%');

        return $signboards = $query->getResult();
    }

    public function getByUser(User $user, $search = null)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('s', 'Si', 't')
            ->from('CibActivityBundle:Tpe', 't')
            ->join('t.store', 's')
            ->join('s.signboard', 'Si')
            ->where('Si.user = ?1')
            ->andWhere('t.tpeNumber LIKE ?2')
            ->setParameter(1,$user)
            ->setParameter(2,'%'.$search.'%');
        $results = $queryBuilder->getQuery();
        return $results->getResult();
    }

}
