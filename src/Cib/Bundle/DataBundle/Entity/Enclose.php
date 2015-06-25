<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 28/10/2014
 * Time: 16:29
 */

namespace Cib\Bundle\DataBundle\Entity;

use Cib\Bundle\CustomerBundle\Entity\Card;
use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class Enclose
 * @package Cib\Bundle\DataBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cib_enclose")
 * @ORM\Entity(repositoryClass="Cib\Bundle\DataBundle\Entity\encloseRepository")
 */
class Enclose {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $encloseId;

    /**
     * @var
     *
     * @ORM\Column(type="date")
     */
    private $dateStartEnclose;

    /**
     * @var
     *
     * @ORM\Column(type="date")
     */
    private $dateStopEnclose;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    private $totalCredit;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    private $totalDebit;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    private $totalBalance;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    private $totalVip;

    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    private $totalPrime;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Store", inversedBy="enclose" , cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId")
     */
    private $store;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Cib\Bundle\DataBundle\Entity\Transaction",mappedBy="enclose", cascade={"persist"})
     */
    private $transaction;


    private $lastEnclose;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountWarningDebit;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountWarningCredit;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountWarningVip;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountWarningPrime;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountWarningBalance;


    /**
     * Get encloseId
     *
     * @return integer 
     */
    public function getEncloseId()
    {
        return $this->encloseId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->transaction = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dateStartEnclose
     *
     * @param \DateTime $dateStartEnclose
     * @return Enclose
     */
    public function setDateStartEnclose($dateStartEnclose)
    {
        $this->dateStartEnclose = $dateStartEnclose;

        return $this;
    }

    /**
     * Get dateStartEnclose
     *
     * @return \DateTime 
     */
    public function getDateStartEnclose()
    {
        return $this->dateStartEnclose;
    }

    /**
     * Set dateStopEnclose
     *
     * @param \DateTime $dateStopEnclose
     * @return Enclose
     */
    public function setDateStopEnclose($dateStopEnclose)
    {
        $this->dateStopEnclose = $dateStopEnclose;

        return $this;
    }

    /**
     * Get dateStopEnclose
     *
     * @return \DateTime 
     */
    public function getDateStopEnclose()
    {
        return $this->dateStopEnclose;
    }

    /**
     * Set totalCredit
     *
     * @param integer $totalCredit
     * @return Enclose
     */
    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $totalCredit;

        return $this;
    }

    /**
     * Get totalCredit
     *
     * @return integer 
     */
    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    /**
     * Set totalDebit
     *
     * @param integer $totalDebit
     * @return Enclose
     */
    public function setTotalDebit($totalDebit)
    {
        $this->totalDebit = $totalDebit;

        return $this;
    }

    /**
     * Get totalDebit
     *
     * @return integer 
     */
    public function getTotalDebit()
    {
        return $this->totalDebit;
    }

    /**
     * Set totalBalance
     *
     * @param integer $totalBalance
     * @return Enclose
     */
    public function setTotalBalance($totalBalance)
    {
        $this->totalBalance = $totalBalance;

        return $this;
    }

    /**
     * Get totalBalance
     *
     * @return integer 
     */
    public function getTotalBalance()
    {
        return $this->totalBalance;
    }

    /**
     * Set totalVip
     *
     * @param integer $totalVip
     * @return Enclose
     */
    public function setTotalVip($totalVip)
    {
        $this->totalVip = $totalVip;

        return $this;
    }

    /**
     * Get totalVip
     *
     * @return integer 
     */
    public function getTotalVip()
    {
        return $this->totalVip;
    }

    /**
     * Set totalPrime
     *
     * @param integer $totalPrime
     * @return Enclose
     */
    public function setTotalPrime($totalPrime)
    {
        $this->totalPrime = $totalPrime;

        return $this;
    }

    /**
     * Get totalPrime
     *
     * @return integer 
     */
    public function getTotalPrime()
    {
        return $this->totalPrime;
    }

    /**
     * Set store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     * @return Enclose
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
     * Add transaction
     *
     * @param \Cib\Bundle\DataBundle\Entity\Transaction $transaction
     * @return Enclose
     */
    public function addTransaction(\Cib\Bundle\DataBundle\Entity\Transaction $transaction)
    {
        $this->transaction[] = $transaction;
        $transaction->setEnclose($this);
        $transaction->setIsEnclosed(true);

        return $this;
    }

    /**
     * Remove transaction
     *
     * @param \Cib\Bundle\DataBundle\Entity\Transaction $transaction
     */
    public function removeTransaction(\Cib\Bundle\DataBundle\Entity\Transaction $transaction)
    {
        $this->transaction->removeElement($transaction);
    }

    /**
     * Get transaction
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTransaction()
    {
        return $this->transaction;
    }



    /**
     * Set amountWarning
     *
     * @param integer $amountWarning
     * @return Enclose
     */
    public function setAmountWarning($amountWarning)
    {
        $this->amountWarning = $amountWarning;

        return $this;
    }

    /**
     * Get amountWarning
     *
     * @return integer 
     */
    public function getAmountWarning()
    {
        return $this->amountWarning;
    }

    /**
     * Set amountWarningDebit
     *
     * @param integer $amountWarningDebit
     * @return Enclose
     */
    public function setAmountWarningDebit($amountWarningDebit)
    {
        $this->amountWarningDebit = $amountWarningDebit;

        return $this;
    }

    /**
     * Get amountWarningDebit
     *
     * @return integer 
     */
    public function getAmountWarningDebit()
    {
        return $this->amountWarningDebit;
    }

    /**
     * Set amountWarningCredit
     *
     * @param integer $amountWarningCredit
     * @return Enclose
     */
    public function setAmountWarningCredit($amountWarningCredit)
    {
        $this->amountWarningCredit = $amountWarningCredit;

        return $this;
    }

    /**
     * Get amountWarningCredit
     *
     * @return integer 
     */
    public function getAmountWarningCredit()
    {
        return $this->amountWarningCredit;
    }

    /**
     * Set amountWarningVip
     *
     * @param integer $amountWarningVip
     * @return Enclose
     */
    public function setAmountWarningVip($amountWarningVip)
    {
        $this->amountWarningVip = $amountWarningVip;

        return $this;
    }

    /**
     * Get amountWarningVip
     *
     * @return integer 
     */
    public function getAmountWarningVip()
    {
        return $this->amountWarningVip;
    }

    /**
     * Set amountWarningPrime
     *
     * @param integer $amountWarningPrime
     * @return Enclose
     */
    public function setAmountWarningPrime($amountWarningPrime)
    {
        $this->amountWarningPrime = $amountWarningPrime;

        return $this;
    }

    /**
     * Get amountWarningPrime
     *
     * @return integer 
     */
    public function getAmountWarningPrime()
    {
        return $this->amountWarningPrime;
    }

    /**
     * Set amountWarningBalance
     *
     * @param integer $amountWarningBalance
     * @return Enclose
     */
    public function setAmountWarningBalance($amountWarningBalance)
    {
        $this->amountWarningBalance = $amountWarningBalance;

        return $this;
    }

    /**
     * Get amountWarningBalance
     *
     * @return integer 
     */
    public function getAmountWarningBalance()
    {
        return $this->amountWarningBalance;
    }

    /**
     * Set lastEnclose
     *
     * @param Enclose|string $lastEnclose
     * @return Enclose
     */
    public function setLastEnclose(Enclose $lastEnclose)
    {
        $this->lastEnclose = $lastEnclose;

        return $this;
    }

    /**
     * Get lastEnclose
     *
     * @return string 
     */
    public function getLastEnclose()
    {
        return $this->lastEnclose;
    }
}
