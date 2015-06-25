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
 * @ORM\Table(name="cib_price")
 * @ORM\Entity(repositoryClass="Cib\Bundle\ActivityBundle\Entity\priceRepository")
 */
class Price
{

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $priceId;

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
     * Get priceId
     *
     * @return integer 
     */
    public function getPriceId()
    {
        return $this->priceId;
    }

    /**
     * Set priceLabel
     *
     * @param string $priceLabel
     * @return Price
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
     * @return Price
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
}
