<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 16/09/14
 * Time: 09:34
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
 * Class Transaction
 * @package Cib\Bundle\DataBundle\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="cib_transaction",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="transaction_idx", columns={"dateTransaction", "typeTransaction", "amountTransaction", "cardId", "tpeId"})}))
 * @ORM\Entity(repositoryClass="Cib\Bundle\DataBundle\Entity\transactionRepository")
 *
 */
class Transaction {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $transactionId;

    /**
     * @var
     *
     * @ORM\Column(type="datetime")
     */
    private $dateTransaction;

    /**
     * @var
     *
     * @ORM\Column(type="string", length=1)
     */
    private $typeTransaction;


    /**
     * @var
     *
     * @ORM\Column(type="integer")
     */
    private $pmeTransaction;

    /**
     * @var
     *
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $amountTransaction;

    /**
     * @var
     *
     * @ORM\Column(type="decimal", precision=6, scale=2, nullable=true)
     */
    private $primeTransaction;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isVipTransaction;

    /**
     * @var
     *
     * @ORM\Column(type="boolean")
     */
    private $isEnclosed = false;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\CustomerBundle\Entity\Card", inversedBy="transaction", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="cardId", referencedColumnName="cardId")
     */
    private $card;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\CustomerBundle\Entity\Client", inversedBy="transaction", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="clientId", referencedColumnName="clientId")
     */
    private $client;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Tpe",  cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="tpeId", referencedColumnName="tpeId")
     */
    private $tpe;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Store",cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId")
     */
    private $store;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\DataBundle\Entity\Enclose", inversedBy="transaction" ,cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="encloseId", referencedColumnName="encloseId", nullable=true)
     */
    private $enclose;

    public function __construct($dateTransaction,$typeTransaction,$pmeTransaction,$amountTransaction,$primeTransaction,$cardNumber,$beforeTransaction,$afterTransaction,$tpeNumber,$isVipTransaction,$entityManager)
    {
        $this->dateTransaction = new \DateTime($dateTransaction);
        $this->typeTransaction = $typeTransaction;
        $this->pmeTransaction = $pmeTransaction;
        $this->amountTransaction = $amountTransaction/100;
        $this->primeTransaction = $primeTransaction/100;
        $this->isVipTransaction = $isVipTransaction;
        $this->card = $entityManager->getRepository('CibCustomerBundle:Card')->findOneBy(array('cardNumber' => $cardNumber));
        if(!$this->card)
        {
            $time = new \DateTime('+1 year');
            $this->card = new Card();
            $this->card->setCardNumber($cardNumber);
            $this->card->setCardValidity($time);
            $this->card->setIsActive(true);

        }

        if($typeTransaction == 'D')
            $this->card->setMoneyAmount1($this->card->getMoneyAmount1() - ($amountTransaction/100));
        else
            $this->card->setMoneyAmount1($this->card->getMoneyAmount1() + ($amountTransaction/100));
        $this->tpe = $entityManager->getRepository('CibActivityBundle:Tpe')->findOneBy(array('tpeNumber' => $tpeNumber));
        $this->client = $this->card->getClient();
        $this->store = $this->tpe->getStore();
    }

    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set dateTransaction
     *
     * @param \DateTime $dateTransaction
     * @return Transaction
     */
    public function setDateTransaction($dateTransaction)
    {
        $this->dateTransaction = $dateTransaction;

        return $this;
    }

    /**
     * Get dateTransaction
     *
     * @return \DateTime 
     */
    public function getDateTransaction()
    {
        return $this->dateTransaction;
    }

    /**
     * Set typeTransaction
     *
     * @param string $typeTransaction
     * @return Transaction
     */
    public function setTypeTransaction($typeTransaction)
    {
        $this->typeTransaction = $typeTransaction;

        return $this;
    }

    /**
     * Get typeTransaction
     *
     * @return string 
     */
    public function getTypeTransaction()
    {
        return $this->typeTransaction;
    }

    /**
     * Set pmeTransaction
     *
     * @param integer $pmeTransaction
     * @return Transaction
     */
    public function setPmeTransaction($pmeTransaction)
    {
        $this->pmeTransaction = $pmeTransaction;

        return $this;
    }

    /**
     * Get pmeTransaction
     *
     * @return integer 
     */
    public function getPmeTransaction()
    {
        return $this->pmeTransaction;
    }

    /**
     * Set amountTransaction
     *
     * @param integer $amountTransaction
     * @return Transaction
     */
    public function setAmountTransaction($amountTransaction)
    {
        $this->amountTransaction = $amountTransaction;

        return $this;
    }

    /**
     * Get amountTransaction
     *
     * @return integer 
     */
    public function getAmountTransaction()
    {
        return $this->amountTransaction;
    }

    /**
     * Set primeTransaction
     *
     * @param integer $primeTransaction
     * @return Transaction
     */
    public function setPrimeTransaction($primeTransaction)
    {
        $this->primeTransaction = $primeTransaction;

        return $this;
    }

    /**
     * Get primeTransaction
     *
     * @return integer 
     */
    public function getPrimeTransaction()
    {
        return $this->primeTransaction;
    }

    /**
     * Set isVipTransaction
     *
     * @param boolean $isVipTransaction
     * @return Transaction
     */
    public function setIsVipTransaction($isVipTransaction)
    {
        $this->isVipTransaction = $isVipTransaction;

        return $this;
    }

    /**
     * Get isVipTransaction
     *
     * @return boolean 
     */
    public function getIsVipTransaction()
    {
        return $this->isVipTransaction;
    }

    /**
     * Set card
     *
     * @param \Cib\Bundle\CustomerBundle\Entity\Card $card
     * @return Transaction
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
     * @return Transaction
     */
    public function setClient(\Cib\Bundle\CustomerBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    public function getDataDirectory()
    {
        return 'data';
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
     * Set tpe
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Tpe $tpe
     * @return Transaction
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
     * @return Transaction
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

    public function setIsEnclosed($isEnclosed)
    {
        $this->isEnclosed = $isEnclosed;
    }

    public function getIsEnclosed()
    {
        return $this->isEnclosed;
    }

    /**
     * Set enclose
     *
     * @param \Cib\Bundle\DataBundle\Entity\Enclose $enclose
     * @return Transaction
     */
    public function setEnclose(\Cib\Bundle\DataBundle\Entity\Enclose $enclose = null)
    {
        $this->enclose = $enclose;

        return $this;
    }

    /**
     * Get enclose
     *
     * @return \Cib\Bundle\DataBundle\Entity\Enclose 
     */
    public function getEnclose()
    {
        return $this->enclose;
    }
}
