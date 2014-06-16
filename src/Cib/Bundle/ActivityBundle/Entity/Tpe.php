<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 12/06/14
 * Time: 12:03
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cib_tpe")
 * @UniqueEntity(fields="tpeNumber", message="un tpe portant ce numéro éxiste déjà")
 */
class Tpe
{
    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tpeId;

    /**
     * @var
     *
     * @ORM\Column(type="text")
     * @Assert\Length(min=6,max=8,minMessage="Le numéro de tpe doit être composé de 6 caractères minimum",maxMessage="Le numéro de tpe ne peut pas excéder 8 caractères")
     * @Assert\Regex(pattern="/(^\d{6,8}$)/", message="le numéro de terminal est composé uniquement de 6 à 8 chiffres")
     * @Assert\NotBlank()
     */
    private $tpeNumber;

//    /**
//     * @var
//     *
//     * @ORM\Column()
//     */
//    private $parameter;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Store", cascade={"persist"})
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId", onDelete="SET NULL")
     */
    private $store;


    private $token;

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
     return $this->token;
    }

    /**
     * Get tpeId
     *
     * @return integer 
     */
    public function getTpeId()
    {
        return $this->tpeId;
    }

    /**
     * Set tpeNumber
     *
     * @param string $tpeNumber
     * @return Tpe
     */
    public function setTpeNumber($tpeNumber)
    {
        $this->tpeNumber = $tpeNumber;

        return $this;
    }

    /**
     * Get tpeNumber
     *
     * @return string 
     */
    public function getTpeNumber()
    {
        return $this->tpeNumber;
    }

    /**
     * Set store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     * @return Tpe
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
}
