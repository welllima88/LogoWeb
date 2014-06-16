<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 16:02
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cib_signboard")
 * @UniqueEntity(fields="signboardName", message="une enseigne portant ce nom existe déjà")
 * @UniqueEntity(fields="signboardNumber", message="une enseigne portant ce numéro existe déjà")
 */
class Signboard
{

    /**
     * @var
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $signboardId;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $signboardName;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $signboardNumber;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Store", mappedBy="signboard", cascade={"persist"})
     */
    private $store;


    private $token;


    public function setToken($token)
    {
        $this->token = $token;
    }

    public function  getToken()
    {
        return $this->token;
    }


    /**
     * Get signboardId
     *
     * @return integer 
     */
    public function getSignboardId()
    {
        return $this->signboardId;
    }

    /**
     * Set signboardName
     *
     * @param string $signboardName
     * @return Signboard
     */
    public function setSignboardName($signboardName)
    {
        $this->signboardName = $signboardName;

        return $this;
    }

    /**
     * Get signboardName
     *
     * @return string 
     */
    public function getSignboardName()
    {
        return $this->signboardName;
    }

    /**
     * Set signboardNumber
     *
     * @param string $signboardNumber
     * @return Signboard
     */
    public function setSignboardNumber($signboardNumber)
    {
        $this->signboardNumber = $signboardNumber;

        return $this;
    }

    /**
     * Get signboardNumber
     *
     * @return string 
     */
    public function getSignboardNumber()
    {
        return $this->signboardNumber;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->store = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     * @return Signboard
     */
    public function addStore(\Cib\Bundle\ActivityBundle\Entity\Store $store)
    {
        $this->store[] = $store;

        return $this;
    }

    /**
     * Remove store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     */
    public function removeStore(\Cib\Bundle\ActivityBundle\Entity\Store $store)
    {
        $this->store->removeElement($store);
    }

    /**
     * Get store
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStore()
    {
        return $this->store;
    }
}
