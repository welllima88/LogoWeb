<?php

namespace Cib\Bundle\DataBundle\Entity;

use Cib\Bundle\ActivityBundle\Entity\Store;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;

/**
 * telecollecteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class telecollecteRepository extends EntityRepository
{

    public function getTelecollectes($store = null,$date = null)
    {
        $arrayResult = new ArrayCollection();
        if($date)
            $date = new \DateTime($date);
        else
            $date = new \DateTime();
            $dql = "SELECT e FROM CibDataBundle:Telecollecte e WHERE e.store = ".$store." AND e.date LIKE '".$date->format('Y-m-d')."'";
            $query = $this->getEntityManager()->createQuery($dql);
            $results = $query->getOneOrNullResult();
//        var_dump($results);
            return $results;
//                $arrayResult->add($results);

//        }


//        return $arrayResult;
    }
}
