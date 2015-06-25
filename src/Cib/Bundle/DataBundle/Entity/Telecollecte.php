<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 18/12/2014
 * Time: 14:05
 */

namespace Cib\Bundle\DataBundle\Entity;

use Cib\Bundle\ActivityBundle\Entity\Store;
use Cib\Bundle\ActivityBundle\Entity\Tpe;
use Cib\Bundle\CustomerBundle\Entity\Card;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Class Telecollecte
 * @package Cib\Bundle\DataBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cib_telecollecte")
 * @ORM\Entity(repositoryClass="Cib\Bundle\DataBundle\Entity\telecollecteRepository")
 */
class Telecollecte
{

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $telecollecteId;

    /**
     * @var
     *
     * @ORM\Column(type="date")
     */
    private $date;


    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Tpe", inversedBy="telecollecte")
     * @ORM\JoinColumn(name="tpeId", referencedColumnName="tpeId")
     */
    private $tpe;


    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Store", inversedBy="telecollecte")
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId")
     */
    private $store;


    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    private $pathFile;

    /**
     * Get telecollecteId
     *
     * @return integer 
     */
    public function getTelecollecteId()
    {
        return $this->telecollecteId;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Telecollecte
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set pathFile
     *
     * @param string $pathFile
     * @return Telecollecte
     */
    public function setPathFile($pathFile)
    {
        $this->pathFile = $pathFile;

        return $this;
    }

    /**
     * Get pathFile
     *
     * @return string 
     */
    public function getPathFile()
    {
        return $this->pathFile;
    }

    /**
     * Set tpe
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Tpe $tpe
     * @return Telecollecte
     */
    public function setTpe(\Cib\Bundle\ActivityBundle\Entity\Tpe $tpe = null)
    {
        $this->tpe = $tpe;

        return $this;
    }

    /**
     * Get tpe
     *
     * @return \Cib\Bundle\ActivityBundle\Entity\Tpe 
     */
    public function getTpe()
    {
        return $this->tpe;
    }

    /**
     * Set store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     * @return Telecollecte
     */
    public function setStore(\Cib\Bundle\ActivityBundle\Entity\Store $store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \Cib\Bundle\ActivityBundle\Entity\Store 
     */
    public function getStore()
    {
        return $this->store;
    }

    public function __construct($date,$pathFile,Tpe $tpe,Store $store )
    {
        $this->date = $date;
        $this->pathFile = $pathFile;
        $this->store = $store;
        $store->addTellecolecte($this);
        $this->tpe = $tpe;
        $tpe->addTelecollecte($this);
        return $this;
    }
}
