<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 03/11/2014
 * Time: 17:23
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="cib_registerPrice")
 * @ORM\Entity(repositoryClass="Cib\Bundle\ActivityBundle\Entity\registerPriceRepository")
 */
class registerPrice
{

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $registerPriceId;

    /**
     * @var
     *
     * @ORM\Column(type="text")
     */
    private $priceLabel;

    /**
     * @var
     *
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $priceAmount;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Cib\Bundle\CustomerBundle\Entity\Client", mappedBy="registerPrice")
     */
    private $client;

    /**
     * Get priceId
     *
     * @return integer 
     */
    public function getRegisterPriceId()
    {
        return $this->registerPriceId;
    }

    /**
     * Set priceLabel
     *
     * @param string $priceLabel
     * @return registerPrice
     */
    public function setPriceLabel($priceLabel)
    {
        $this->priceLabel = $priceLabel;

        return $this;
    }

    /**
     * Get priceLabel
     *
     * @return string 
     */
    public function getPriceLabel()
    {
        return $this->priceLabel;
    }

    /**
     * Set priceAmount
     *
     * @param string $priceAmount
     * @return registerPrice
     */
    public function setPriceAmount($priceAmount)
    {
        $this->priceAmount = $priceAmount;

        return $this;
    }

    /**
     * Get priceAmount
     *
     * @return string 
     */
    public function getPriceAmount()
    {
        return $this->priceAmount;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add client
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Client $client
     * @return registerPrice
     */
    public function addClient(\Cib\Bundle\CustomerBundle\Entity\Client $client)
    {
        $this->client[] = $client;

        return $this;
    }

    /**
     * Remove client
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Client $client
     */
    public function removeClient(\Cib\Bundle\CustomerBundle\Entity\Client $client)
    {
        $this->client->removeElement($client);
    }

    /**
     * Get client
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getClient()
    {
        return $this->client;
    }
}
