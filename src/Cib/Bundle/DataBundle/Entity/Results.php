<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 18/09/2014
 * Time: 10:05
 */

namespace Cib\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Class Results
 * @package Cib\Bundle\DataBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cib_results")
 * @ORM\Entity(repositoryClass="Cib\Bundle\DataBundle\Entity\resultsRepository")
 * @UniqueEntity(fields="name", message="un enregistrment portant ce nom existe déjà")
 *
 */
class Results {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $resultId;

    /**
     * @var
     *
     * @ORM\Column(type="text")
     *
     */
    private $month;

    /**
     * @var
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateStart;

    /**
     * @var
     *
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateStop;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\CustomerBundle\Entity\Card")
     * @ORM\JoinColumn(name="cardId", referencedColumnName="cardId")
     */
    private $card;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\CustomerBundle\Entity\Client")
     * @ORM\JoinColumn(name="clientId", referencedColumnName="clientId")
     */
    private $client;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Store")
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId")
     */
    private $store;

    /**
     * @var
     *
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * Get resultId
     *
     * @return integer 
     */
    public function getResultId()
    {
        return $this->resultId;
    }

    /**
     * Set month
     *
     * @param string $month
     * @return Results
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return string 
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return Results
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateStop
     *
     * @param \DateTime $dateStop
     * @return Results
     */
    public function setDateStop($dateStop)
    {
        $this->dateStop = $dateStop;

        return $this;
    }

    /**
     * Get dateStop
     *
     * @return \DateTime 
     */
    public function getDateStop()
    {
        return $this->dateStop;
    }

    /**
     * Set card
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Card $card
     * @return Results
     */
    public function setCard(\Cib\Bundle\CustomerBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \Cib\Bundle\CustomerBundle\Entity\Card 
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set client
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Client $client
     * @return Results
     */
    public function setClient(\Cib\Bundle\CustomerBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Cib\Bundle\CustomerBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     * @return Results
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

    /**
     * Set name
     *
     * @param string $name
     * @return Results
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
