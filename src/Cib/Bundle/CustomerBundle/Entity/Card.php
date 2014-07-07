<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 17/06/14
 * Time: 15:04
 */

namespace Cib\Bundle\CustomerBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="cib_card")
 * @UniqueEntity(fields="cardNumber", message="une carte portant ce numéro existe déjà")
 */
class Card
{


    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $cardId;

    /**
     * @var
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^\d{8,10}$/")
     */
    private $cardNumber;


    /**
     * @var
     *
     * @ORM\Column(type="date")
     */
    private $cardValidity;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $moneyAmount1;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $unitAmount1;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $moneyAmount2;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $unitAmount2;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $moneyAmount3;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $unitAmount3;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $moneyAmount4;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $unitAmount4;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $moneyAmount5;

    /**
     * @var
     *
     * @ORM\Column(type="integer",nullable=true)
     */
    private $unitAmount5;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $isActive;


    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Signboard")
     * @ORM\JoinColumn(name="signboardId", referencedColumnName="signboardId", onDelete="SET NULL" )
     */
    private $signboard;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="card")
     * @ORM\JoinColumn(name="clientId", referencedColumnName="clientId")
     */
    private $client;


    private $token;




    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
    /**
     * Get cardId
     *
     * @return integer 
     */
    public function getCardId()
    {
        return $this->cardId;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     * @return Card
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string 
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set cardValidity
     *
     * @param \DateTime $cardValidity
     * @return Card
     */
    public function setCardValidity($cardValidity)
    {
        $this->cardValidity = $cardValidity;

        return $this;
    }

    /**
     * Get cardValidity
     *
     * @return \DateTime 
     */
    public function getCardValidity()
    {
        return $this->cardValidity;
    }

    /**
     * Set Pme1
     *
     * @param integer $pme1
     * @return Card
     */
    public function setPme1($pme1)
    {
        $this->Pme1 = $pme1;

        return $this;
    }

    /**
     * Get Pme1
     *
     * @return integer 
     */
    public function getPme1()
    {
        return $this->Pme1;
    }

    /**
     * Set Pme2
     *
     * @param integer $pme2
     * @return Card
     */
    public function setPme2($pme2)
    {
        $this->Pme2 = $pme2;

        return $this;
    }

    /**
     * Get Pme2
     *
     * @return integer 
     */
    public function getPme2()
    {
        return $this->Pme2;
    }

    /**
     * Set Pme3
     *
     * @param integer $pme3
     * @return Card
     */
    public function setPme3($pme3)
    {
        $this->Pme3 = $pme3;

        return $this;
    }

    /**
     * Get Pme3
     *
     * @return integer 
     */
    public function getPme3()
    {
        return $this->Pme3;
    }

    /**
     * Set Pme4
     *
     * @param integer $pme4
     * @return Card
     */
    public function setPme4($pme4)
    {
        $this->Pme4 = $pme4;

        return $this;
    }

    /**
     * Get Pme4
     *
     * @return integer 
     */
    public function getPme4()
    {
        return $this->Pme4;
    }

    /**
     * Set Pme5
     *
     * @param integer $pme5
     * @return Card
     */
    public function setPme5($pme5)
    {
        $this->Pme5 = $pme5;

        return $this;
    }

    /**
     * Get Pme5
     *
     * @return integer 
     */
    public function getPme5()
    {
        return $this->Pme5;
    }

    /**
     * Set moneyAmount1
     *
     * @param integer $moneyAmount1
     * @return Card
     */
    public function setMoneyAmount1($moneyAmount1)
    {
        $this->moneyAmount1 = $moneyAmount1;

        return $this;
    }

    /**
     * Get moneyAmount1
     *
     * @return integer 
     */
    public function getMoneyAmount1()
    {
        return $this->moneyAmount1;
    }

    /**
     * Set unitAmount1
     *
     * @param integer $unitAmount1
     * @return Card
     */
    public function setUnitAmount1($unitAmount1)
    {
        $this->unitAmount1 = $unitAmount1;

        return $this;
    }

    /**
     * Get unitAmount1
     *
     * @return integer 
     */
    public function getUnitAmount1()
    {
        return $this->unitAmount1;
    }

    /**
     * Set moneyAmount2
     *
     * @param integer $moneyAmount2
     * @return Card
     */
    public function setMoneyAmount2($moneyAmount2)
    {
        $this->moneyAmount2 = $moneyAmount2;

        return $this;
    }

    /**
     * Get moneyAmount2
     *
     * @return integer 
     */
    public function getMoneyAmount2()
    {
        return $this->moneyAmount2;
    }

    /**
     * Set unitAmount2
     *
     * @param integer $unitAmount2
     * @return Card
     */
    public function setUnitAmount2($unitAmount2)
    {
        $this->unitAmount2 = $unitAmount2;

        return $this;
    }

    /**
     * Get unitAmount2
     *
     * @return integer 
     */
    public function getUnitAmount2()
    {
        return $this->unitAmount2;
    }

    /**
     * Set moneyAmount3
     *
     * @param integer $moneyAmount3
     * @return Card
     */
    public function setMoneyAmount3($moneyAmount3)
    {
        $this->moneyAmount3 = $moneyAmount3;

        return $this;
    }

    /**
     * Get moneyAmount3
     *
     * @return integer 
     */
    public function getMoneyAmount3()
    {
        return $this->moneyAmount3;
    }

    /**
     * Set unitAmount3
     *
     * @param integer $unitAmount3
     * @return Card
     */
    public function setUnitAmount3($unitAmount3)
    {
        $this->unitAmount3 = $unitAmount3;

        return $this;
    }

    /**
     * Get unitAmount3
     *
     * @return integer 
     */
    public function getUnitAmount3()
    {
        return $this->unitAmount3;
    }

    /**
     * Set moneyAmount4
     *
     * @param integer $moneyAmount4
     * @return Card
     */
    public function setMoneyAmount4($moneyAmount4)
    {
        $this->moneyAmount4 = $moneyAmount4;

        return $this;
    }

    /**
     * Get moneyAmount4
     *
     * @return integer 
     */
    public function getMoneyAmount4()
    {
        return $this->moneyAmount4;
    }

    /**
     * Set unitAmount4
     *
     * @param integer $unitAmount4
     * @return Card
     */
    public function setUnitAmount4($unitAmount4)
    {
        $this->unitAmount4 = $unitAmount4;

        return $this;
    }

    /**
     * Get unitAmount4
     *
     * @return integer 
     */
    public function getUnitAmount4()
    {
        return $this->unitAmount4;
    }

    /**
     * Set moneyAmount5
     *
     * @param integer $moneyAmount5
     * @return Card
     */
    public function setMoneyAmount5($moneyAmount5)
    {
        $this->moneyAmount5 = $moneyAmount5;

        return $this;
    }

    /**
     * Get moneyAmount5
     *
     * @return integer 
     */
    public function getMoneyAmount5()
    {
        return $this->moneyAmount5;
    }

    /**
     * Set unitAmount5
     *
     * @param integer $unitAmount5
     * @return Card
     */
    public function setUnitAmount5($unitAmount5)
    {
        $this->unitAmount5 = $unitAmount5;

        return $this;
    }

    /**
     * Get unitAmount5
     *
     * @return integer 
     */
    public function getUnitAmount5()
    {
        return $this->unitAmount5;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Card
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set signboard
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Signboard $signboard
     * @return Card
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

    /**
     * Set client
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Client $client
     * @return Card
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
}
