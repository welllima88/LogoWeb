<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 30/09/2014
 * Time: 16:23
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="cib_club")
 * @ORM\Entity(repositoryClass="Cib\Bundle\ActivityBundle\Entity\clubRepository")
 */
class Club {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $clubId;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $clubNumber;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $clubName;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Cib\Bundle\CustomerBundle\Entity\Client", mappedBy="club")
     */
    private $client;

    /**
     * Get clubId
     *
     * @return integer 
     */
    public function getClubId()
    {
        return $this->clubId;
    }

    /**
     * Set clubNumber
     *
     * @param string $clubNumber
     * @return club
     */
    public function setClubNumber($clubNumber)
    {
        $this->clubNumber = $clubNumber;

        return $this;
    }

    /**
     * Get clubNumber
     *
     * @return string 
     */
    public function getClubNumber()
    {
        return $this->clubNumber;
    }

    /**
     * Set clubName
     *
     * @param string $clubName
     * @return club
     */
    public function setClubName($clubName)
    {
        $this->clubName = $clubName;

        return $this;
    }

    /**
     * Get clubName
     *
     * @return string 
     */
    public function getClubName()
    {
        return $this->clubName;
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
     * @return Club
     */
    public function addClient(\Cib\Bundle\CustomerBundle\Entity\Client $client)
    {
        $this->client[] = $client;
        $client->setClub($this);

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
