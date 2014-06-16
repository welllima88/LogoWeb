<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 16:18
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cib_store")
 * @UniqueEntity(fields="storeName", message="une magasin portant ce nom existe déjà")
 */
class Store
{

    /**
     * @var
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $storeId;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min=3,max=50,minMessage="Le nom du magasin doit-être composé d'au moins 3 caractères",maxMessage="Le nom du magasin ne peut pas excéder 50 caractères")
     */
    private $storeName;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\Length(min=3,max=50,minMessage="L'adresse du magasin doit-être composé d'au moins 3 caractères",maxMessage="L'adresse du magasin ne peut pas excéder 50 caractères")
     */
    private $storeAddress;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\Length(min=5,max=5,minMessage="Le code postal doit-être composé de 5 caractères",maxMessage="Le code postal doit-être composé de 5 caractères")
     * @Assert\Regex(pattern="/[0-9]{5}/",
     *                  message="Le code postal ne peut pas contenir de lettres et uniquement 5 chiffres")
     */
    private $storeZipCode;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\Length(min=3,max=50,minMessage="La ville du magasin doit-être composé d'au moins 3 caractères",maxMessage="La ville du magasin ne peut pas excéder 50 caractères")
     */
    private $storeCity;

    /**
     * @var
     * @ORM\Column(type="string")
     * @Assert\Length(min=10,max=15,minMessage="Le numéro de téléphone doit-être composé d'au moins 10 caractères",maxMessage="Le numéro de téléphone ne peut pas excéder 15 caractères")
     */
    private $storePhone;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Signboard", inversedBy="store")
     * @ORM\JoinColumn(name="signboardId", referencedColumnName="signboardId", onDelete="SET NULL" )
     */
    private $signboard;


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
     * Get storeId
     *
     * @return integer 
     */
    public function getStoreId()
    {
        return $this->storeId;
    }

    /**
     * Set storeName
     *
     * @param string $storeName
     * @return Store
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * Get storeName
     *
     * @return string 
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * Set storeAddress
     *
     * @param string $storeAddress
     * @return Store
     */
    public function setStoreAddress($storeAddress)
    {
        $this->storeAddress = $storeAddress;

        return $this;
    }

    /**
     * Get storeAddress
     *
     * @return string 
     */
    public function getStoreAddress()
    {
        return $this->storeAddress;
    }

    /**
     * Set storeZipCode
     *
     * @param string $storeZipCode
     * @return Store
     */
    public function setStoreZipCode($storeZipCode)
    {
        $this->storeZipCode = $storeZipCode;

        return $this;
    }

    /**
     * Get storeZipCode
     *
     * @return string 
     */
    public function getStoreZipCode()
    {
        return $this->storeZipCode;
    }

    /**
     * Set storeCity
     *
     * @param string $storeCity
     * @return Store
     */
    public function setStoreCity($storeCity)
    {
        $this->storeCity = $storeCity;

        return $this;
    }

    /**
     * Get storeCity
     *
     * @return string 
     */
    public function getStoreCity()
    {
        return $this->storeCity;
    }

    /**
     * Set storePhone
     *
     * @param string $storePhone
     * @return Store
     */
    public function setStorePhone($storePhone)
    {
        $this->storePhone = $storePhone;

        return $this;
    }

    /**
     * Get storePhone
     * @return string 
     */
    public function getStorePhone()
    {
        return $this->storePhone;
    }

    /**
     * Set signboard
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Signboard $signboard
     * @return Store
     */
    public function setSignboard(\Cib\Bundle\ActivityBundle\Entity\Signboard $signboard = null)
    {
        $this->signboard = $signboard;

        return $this;
    }

    /**
     * Get signboard
     *
     * @return \Cib\Bundle\ActivityBundle\Entity\Signboard 
     */
    public function getSignboard()
    {
        return $this->signboard;
    }
}
