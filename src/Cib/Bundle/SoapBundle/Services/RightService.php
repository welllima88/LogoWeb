<?php
/**
 * Created by PhpStorm.
 * User: Gary
 * Date: 02/06/2015
 * Time: 09:36
 */

namespace Cib\Bundle\SoapBundle\Services;
use Cib\Bundle\ActivityBundle\Entity\Tpe;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Validator\Constraints\Null;

class RightService {

    protected $em;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    public function numTpeExist($numTpe)
    {
        $tpeRepo = $this->em->getRepository('CibActivityBundle:Tpe');

        $tpe = $tpeRepo->findOneBy(array('tpeNumber' => $numTpe));

        return $tpe;
    }

    public function rightCheck($numTpe)
    {
        $tpe = $this->numTpeExist($numTpe);
        if ($tpe == NULL)
            return '0';
        else if ($tpe)
            return '1';
    }
}