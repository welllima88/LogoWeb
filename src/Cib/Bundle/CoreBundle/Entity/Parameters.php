<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 01/07/14
 * Time: 16:50
 */

namespace Cib\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cib_parameters")
 */
class Parameters
{
    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $parameterId;

    /**
     * @var
     *
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $Pme1;

    /**
     * @var
     *
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $Pme2;

    /**
     * @var
     *
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $Pme3;

    /**
     * @var
     *
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $Pme4;

    /**
     * @var
     *
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $Pme5;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    private $ftpUrl;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\Length(max=5, maxMessage="le numéro de port ne peut contenir plus de 5 caractères")
     * @Assert\Regex(pattern="/^\d{1,5}$/", message="le numéro de port est uniquement composé de chiffres")
     */
    private $ftpPort;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    private $ftpUser;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    private $ftpPassword;

    /**
     * Get parameterId
     *
     * @return integer 
     */
    public function getParameterId()
    {
        return $this->parameterId;
    }

    /**
     * Set Pme1
     *
     * @param boolean $pme1
     * @return Parameters
     */
    public function setPme1($pme1)
    {
        $this->Pme1 = $pme1;

        return $this;
    }

    /**
     * Get Pme1
     *
     * @return boolean 
     */
    public function getPme1()
    {
        return $this->Pme1;
    }

    /**
     * Set Pme2
     *
     * @param boolean $pme2
     * @return Parameters
     */
    public function setPme2($pme2)
    {
        $this->Pme2 = $pme2;

        return $this;
    }

    /**
     * Get Pme2
     *
     * @return boolean 
     */
    public function getPme2()
    {
        return $this->Pme2;
    }

    /**
     * Set Pme3
     *
     * @param boolean $pme3
     * @return Parameters
     */
    public function setPme3($pme3)
    {
        $this->Pme3 = $pme3;

        return $this;
    }

    /**
     * Get Pme3
     *
     * @return boolean 
     */
    public function getPme3()
    {
        return $this->Pme3;
    }

    /**
     * Set Pme4
     *
     * @param boolean $pme4
     * @return Parameters
     */
    public function setPme4($pme4)
    {
        $this->Pme4 = $pme4;

        return $this;
    }

    /**
     * Get Pme4
     *
     * @return boolean 
     */
    public function getPme4()
    {
        return $this->Pme4;
    }

    /**
     * Set Pme5
     *
     * @param boolean $pme5
     * @return Parameters
     */
    public function setPme5($pme5)
    {
        $this->Pme5 = $pme5;

        return $this;
    }

    /**
     * Get Pme5
     *
     * @return boolean 
     */
    public function getPme5()
    {
        return $this->Pme5;
    }

    /**
     * Set ftpUrl
     *
     * @param string $ftpUrl
     * @return Parameters
     */
    public function setFtpUrl($ftpUrl)
    {
        $this->ftpUrl = $ftpUrl;

        return $this;
    }

    /**
     * Get ftpUrl
     *
     * @return string 
     */
    public function getFtpUrl()
    {
        return $this->ftpUrl;
    }

    /**
     * Set ftpPort
     *
     * @param string $ftpPort
     * @return Parameters
     */
    public function setFtpPort($ftpPort)
    {
        $this->ftpPort = $ftpPort;

        return $this;
    }

    /**
     * Get ftpPort
     *
     * @return string 
     */
    public function getFtpPort()
    {
        return $this->ftpPort;
    }

    /**
     * Set ftpUser
     *
     * @param string $ftpUser
     * @return Parameters
     */
    public function setFtpUser($ftpUser)
    {
        $this->ftpUser = $ftpUser;

        return $this;
    }

    /**
     * Get ftpUser
     *
     * @return string 
     */
    public function getFtpUser()
    {
        return $this->ftpUser;
    }

    /**
     * Set ftpPassword
     *
     * @param string $ftpPassword
     * @return Parameters
     */
    public function setFtpPassword($ftpPassword)
    {
        $this->ftpPassword = $ftpPassword;

        return $this;
    }

    /**
     * Get ftpPassword
     *
     * @return string 
     */
    public function getFtpPassword()
    {
        return $this->ftpPassword;
    }
}
