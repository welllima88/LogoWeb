<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 26/09/2014
 * Time: 10:45
 */

namespace Cib\Bundle\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="cib_bankAccount")
 * @ORM\Entity(repositoryClass="Cib\Bundle\CustomerBundle\Entity\bankAccountRepository")
 *
 * @ExclusionPolicy("all")
 */
class bankAccount {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $bankAccountId;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $debtorName;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $debtorAddress;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $debtorZipCode;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $debtorCity;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $debtorCountry;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $rum;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $creditorCode;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $creditorName;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $creditorAddress;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable = true)
     */
    private $creditorZipCode;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $creditorCity;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $creditorCountry;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $iban;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $bic;


    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    private $dateSign;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $placeSign;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="Cib\Bundle\CustomerBundle\Entity\Client", inversedBy="bankAccount")
     * @ORM\JoinColumn(name="clientId", referencedColumnName="clientId")
     */
    private $client;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $frequency;

    /**
     * Get bankAccountId
     *
     * @return integer 
     */
    public function getBankAccountId()
    {
        return $this->bankAccountId;
    }

    /**
     * Set rum
     *
     * @param string $rum
     * @return bankAccount
     */
    public function setRum($rum)
    {
        $this->rum = $rum;

        return $this;
    }

    /**
     * Get rum
     *
     * @return string 
     */
    public function getRum()
    {
        return $this->rum;
    }

    /**
     * Set activityCode
     *
     * @param $creditorCode
     * @internal param string $activityCode
     * @return bankAccount
     */
    public function setCreditorCode($creditorCode)
    {
        $this->creditorCode= $creditorCode;

        return $this;
    }

    /**
     * Get activityCode
     *
     * @return string 
     */
    public function getCreditorCode()
    {
        return $this->creditorCode;
    }

    /**
     * Set iban
     *
     * @param string $iban
     * @return bankAccount
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string 
     */
    public function getIban()
    {
        return $this->iban;
    }



    /**
     * Set bic
     *
     * @param string $bic
     * @return bankAccount
     */
    public function setBic($bic)
    {
        $this->bic = $bic;

        return $this;
    }

    /**
     * Get bic
     *
     * @return string 
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Set dateSign
     *
     * @param \DateTime $dateSign
     * @return bankAccount
     */
    public function setDateSign($dateSign)
    {
        $this->dateSign = $dateSign;

        return $this;
    }

    /**
     * Get dateSign
     *
     * @return \DateTime 
     */
    public function getDateSign()
    {
        return $this->dateSign;
    }

    /**
     * Set placeSign
     *
     * @param string $placeSign
     * @return bankAccount
     */
    public function setPlaceSign($placeSign)
    {
        $this->placeSign = $placeSign;

        return $this;
    }

    /**
     * Get placeSign
     *
     * @return string 
     */
    public function getPlaceSign()
    {
        return $this->placeSign;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return bankAccount
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set debtorName
     *
     * @param string $debtorName
     * @return bankAccount
     */
    public function setDebtorName($debtorName)
    {
        $this->debtorName = $debtorName;

        return $this;
    }

    /**
     * Get debtorName
     *
     * @return string 
     */
    public function getDebtorName()
    {
        return $this->debtorName;
    }

    /**
     * Set client
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Client $client
     * @return bankAccount
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

    public function getDebtorAddress()
    {
        return $this->debtorAddress;
    }

    public function setDebtorAddress($debtorAddress)
    {
        $this->debtorAddress= $debtorAddress;
    }

    public function getDebtorZipCode()
    {
        return $this->debtorZipCode;
    }

    public function setDebtorZipCode($debtorZipCode)
    {
        $this->debtorZipCode = $debtorZipCode;
    }

    public function getDebtorCity()
    {
        return $this->debtorCity;
    }

    public function setDebtorCity($debtorCity)
    {
        $this->debtorCity = $debtorCity;
    }

    /**
     * Set debtorCountry
     *
     * @param string $debtorCountry
     * @return bankAccount
     */
    public function setDebtorCountry($debtorCountry)
    {
        $this->debtorCountry = $debtorCountry;

        return $this;
    }

    /**
     * Get debtorCountry
     *
     * @return string 
     */
    public function getDebtorCountry()
    {
        return $this->debtorCountry;
    }

    /**
     * Set creditorName
     *
     * @param string $creditorName
     * @return bankAccount
     */
    public function setCreditorName($creditorName)
    {
        $this->creditorName = $creditorName;

        return $this;
    }

    /**
     * Get creditorName
     *
     * @return string 
     */
    public function getCreditorName()
    {
        return $this->creditorName;
    }

    /**
     * Set creditorAddress
     *
     * @param string $creditorAddress
     * @return bankAccount
     */
    public function setCreditorAddress($creditorAddress)
    {
        $this->creditorAddress = $creditorAddress;

        return $this;
    }

    /**
     * Get creditorAddress
     *
     * @return string 
     */
    public function getCreditorAddress()
    {
        return $this->creditorAddress;
    }

    /**
     * Set creditorZipCode
     *
     * @param string $creditorZipCode
     * @return bankAccount
     */
    public function setCreditorZipCode($creditorZipCode)
    {
        $this->creditorZipCode = $creditorZipCode;

        return $this;
    }

    /**
     * Get creditorZipCode
     *
     * @return string 
     */
    public function getCreditorZipCode()
    {
        return $this->creditorZipCode;
    }

    /**
     * Set creditorCity
     *
     * @param string $creditorCity
     * @return bankAccount
     */
    public function setCreditorCity($creditorCity)
    {
        $this->creditorCity = $creditorCity;

        return $this;
    }

    /**
     * Get creditorCity
     *
     * @return string 
     */
    public function getCreditorCity()
    {
        return $this->creditorCity;
    }

    /**
     * Set creditorCountry
     *
     * @param string $creditorCountry
     * @return bankAccount
     */
    public function setCreditorCountry($creditorCountry)
    {
        $this->creditorCountry = $creditorCountry;

        return $this;
    }

    /**
     * Get creditorCountry
     *
     * @return string 
     */
    public function getCreditorCountry()
    {
        return $this->creditorCountry;
    }



    public function __construct()
    {
        $this->setCreditorInformation();
        return $this;
    }

    public function setCreditorInformation()
    {
        $var = parse_ini_file('bundles/cibcustomer/creditor/creditorInformations.ini');

//        var_dump($var);die;
        $this->creditorAddress = $var['address'];
        $this->creditorCode = $var['idSepa'];
        $this->creditorName = $var['name'];
        $this->creditorZipCode = $var['zipCode'];
        $this->creditorCity = $var['city'];
        $this->creditorCountry = $var['country'];
        $this->dateSign = new \DateTime();

    }


    /**
     * Set frequency
     *
     * @param string $frequency
     * @return bankAccount
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;

        return $this;
    }

    /**
     * Get frequency
     *
     * @return string 
     */
    public function getFrequency()
    {
        return $this->frequency;
    }
}
