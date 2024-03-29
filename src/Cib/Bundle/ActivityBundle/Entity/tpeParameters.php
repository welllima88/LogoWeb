<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 03/09/14
 * Time: 17:53
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;


/**
 * @ORM\Entity
 * @ORM\Table(name="cib_tpeParam")
 * @ORM\Entity(repositoryClass="Cib\Bundle\ActivityBundle\Entity\tpeParametersRepository")
 *
 * @ExclusionPolicy("all")
 */
class tpeParameters {

    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tpeParametersId;

    /**
     * @var
     * @ORM\Column(type="text", nullable=true)
     */
    private $ftpHost;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex(pattern="/(^\d{2,5}$)/",message="le numéro de port ne peut contenir que de 2 à 5 chiffres")
     */
    private $ftpPort;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $ftpLogin;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     *
     */
    private $ftpPassword;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ftpMode;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $apnGprs;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $loginGprs;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $passwordGprs;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme1;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme1Unit;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme2;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme2Unit;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme3;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme3Unit;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme4;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme4Unit;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme5;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPme5Unit;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPrime1;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $typePrime1;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPrime1;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelPrime1;
    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPrime2;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $typePrime2;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPrime2;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelPrime2;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPrime3;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $typePrime3;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPrime3;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelPrime3;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPrime4;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $typePrime4;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPrime4;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelPrime4;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isPrime5;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $typePrime5;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountPrime5;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $levelPrime5;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minPurchase;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $header1;


    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $header2;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $header3;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $header4;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $header5;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $footer1;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $footer2;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $footer3;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $footer4;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $footer5;
    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $urlSoap;

    /**
     * @var
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $portSoap;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $fileName;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $typeConnexion;

    /**
     * Get tpeParametersId
     *
     * @return integer 
     */
    public function getTpeParametersId()
    {
        return $this->tpeParametersId;
    }

    /**
     * Set ftpHost
     *
     * @param string $ftpHost
     * @return tpeParameters
     */
    public function setFtpHost($ftpHost)
    {
        $this->ftpHost = $ftpHost;

        return $this;
    }

    /**
     * Get ftpHost
     *
     * @return string 
     */
    public function getFtpHost()
    {
        return $this->ftpHost;
    }

    /**
     * Set ftpPort
     *
     * @param string $ftpPort
     * @return tpeParameters
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
     * Set ftpLogin
     *
     * @param string $ftpLogin
     * @return tpeParameters
     */
    public function setFtpLogin($ftpLogin)
    {
        $this->ftpLogin = $ftpLogin;

        return $this;
    }

    /**
     * Get ftpLogin
     *
     * @return string 
     */
    public function getFtpLogin()
    {
        return $this->ftpLogin;
    }

    /**
     * Set ftpPassword
     *
     * @param string $ftpPassword
     * @return tpeParameters
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

    /**
     * Set ftpMode
     *
     * @param boolean $ftpMode
     * @return tpeParameters
     */
    public function setFtpMode($ftpMode)
    {
        $this->ftpMode = $ftpMode;

        return $this;
    }

    /**
     * Get ftpMode
     *
     * @return boolean 
     */
    public function getFtpMode()
    {
        return $this->ftpMode;
    }

    /**
     * Set apnGprs
     *
     * @param string $apnGprs
     * @return tpeParameters
     */
    public function setApnGprs($apnGprs)
    {
        $this->apnGprs = $apnGprs;

        return $this;
    }

    /**
     * Get apnGprs
     *
     * @return string 
     */
    public function getApnGprs()
    {
        return $this->apnGprs;
    }

    /**
     * Set loginGprs
     *
     * @param string $loginGprs
     * @return tpeParameters
     */
    public function setLoginGprs($loginGprs)
    {
        $this->loginGprs = $loginGprs;

        return $this;
    }

    /**
     * Get loginGprs
     *
     * @return string 
     */
    public function getLoginGprs()
    {
        return $this->loginGprs;
    }

    /**
     * Set passwordGprs
     *
     * @param string $passwordGprs
     * @return tpeParameters
     */
    public function setPasswordGprs($passwordGprs)
    {
        $this->passwordGprs = $passwordGprs;

        return $this;
    }

    /**
     * Get passwordGprs
     *
     * @return string 
     */
    public function getPasswordGprs()
    {
        return $this->passwordGprs;
    }

    /**
     * Set isPme1
     *
     * @param boolean $isPme1
     * @return tpeParameters
     */
    public function setIsPme1($isPme1)
    {
        $this->isPme1 = $isPme1;

        return $this;
    }

    /**
     * Get isPme1
     *
     * @return boolean 
     */
    public function getIsPme1()
    {
        return $this->isPme1;
    }

    /**
     * Set isPme1Unit
     *
     * @param boolean $isPme1Unit
     * @return tpeParameters
     */
    public function setIsPme1Unit($isPme1Unit)
    {
        $this->isPme1Unit = $isPme1Unit;

        return $this;
    }

    /**
     * Get isPme1Unit
     *
     * @return boolean 
     */
    public function getIsPme1Unit()
    {
        return $this->isPme1Unit;
    }

    /**
     * Set isPme2
     *
     * @param boolean $isPme2
     * @return tpeParameters
     */
    public function setIsPme2($isPme2)
    {
        $this->isPme2 = $isPme2;

        return $this;
    }

    /**
     * Get isPme2
     *
     * @return boolean 
     */
    public function getIsPme2()
    {
        return $this->isPme2;
    }

    /**
     * Set isPme2Unit
     *
     * @param boolean $isPme2Unit
     * @return tpeParameters
     */
    public function setIsPme2Unit($isPme2Unit)
    {
        $this->isPme2Unit = $isPme2Unit;

        return $this;
    }

    /**
     * Get isPme2Unit
     *
     * @return boolean 
     */
    public function getIsPme2Unit()
    {
        return $this->isPme2Unit;
    }

    /**
     * Set isPme3
     *
     * @param boolean $isPme3
     * @return tpeParameters
     */
    public function setIsPme3($isPme3)
    {
        $this->isPme3 = $isPme3;

        return $this;
    }

    /**
     * Get isPme3
     *
     * @return boolean 
     */
    public function getIsPme3()
    {
        return $this->isPme3;
    }

    /**
     * Set isPme3Unit
     *
     * @param boolean $isPme3Unit
     * @return tpeParameters
     */
    public function setIsPme3Unit($isPme3Unit)
    {
        $this->isPme3Unit = $isPme3Unit;

        return $this;
    }

    /**
     * Get isPme3Unit
     *
     * @return boolean 
     */
    public function getIsPme3Unit()
    {
        return $this->isPme3Unit;
    }

    /**
     * Set isPme4
     *
     * @param boolean $isPme4
     * @return tpeParameters
     */
    public function setIsPme4($isPme4)
    {
        $this->isPme4 = $isPme4;

        return $this;
    }

    /**
     * Get isPme4
     *
     * @return boolean 
     */
    public function getIsPme4()
    {
        return $this->isPme4;
    }

    /**
     * Set isPme4Unit
     *
     * @param boolean $isPme4Unit
     * @return tpeParameters
     */
    public function setIsPme4Unit($isPme4Unit)
    {
        $this->isPme4Unit = $isPme4Unit;

        return $this;
    }

    /**
     * Get isPme4Unit
     *
     * @return boolean 
     */
    public function getIsPme4Unit()
    {
        return $this->isPme4Unit;
    }

    /**
     * Set isPme5
     *
     * @param boolean $isPme5
     * @return tpeParameters
     */
    public function setIsPme5($isPme5)
    {
        $this->isPme5 = $isPme5;

        return $this;
    }

    /**
     * Get isPme5
     *
     * @return boolean 
     */
    public function getIsPme5()
    {
        return $this->isPme5;
    }

    /**
     * Set isPme5Unit
     *
     * @param boolean $isPme5Unit
     * @return tpeParameters
     */
    public function setIsPme5Unit($isPme5Unit)
    {
        $this->isPme5Unit = $isPme5Unit;

        return $this;
    }

    /**
     * Get isPme5Unit
     *
     * @return boolean 
     */
    public function getIsPme5Unit()
    {
        return $this->isPme5Unit;
    }

    /**
     * Set isPrime1
     *
     * @param boolean $isPrime1
     * @return tpeParameters
     */
    public function setIsPrime1($isPrime1)
    {
        $this->isPrime1 = $isPrime1;

        return $this;
    }

    /**
     * Get isPrime1
     *
     * @return boolean 
     */
    public function getIsPrime1()
    {
        return $this->isPrime1;
    }

    /**
     * Set typePrime1
     *
     * @param string $typePrime1
     * @return tpeParameters
     */
    public function setTypePrime1($typePrime1)
    {
        $this->typePrime1 = $typePrime1;

        return $this;
    }

    /**
     * Get typePrime1
     *
     * @return string 
     */
    public function getTypePrime1()
    {
        return $this->typePrime1;
    }

    /**
     * Set amountPrime1
     *
     * @param integer $amountPrime1
     * @return tpeParameters
     */
    public function setAmountPrime1($amountPrime1)
    {
        $this->amountPrime1 = $amountPrime1;

        return $this;
    }

    /**
     * Get amountPrime1
     *
     * @return integer 
     */
    public function getAmountPrime1()
    {
        return $this->amountPrime1;
    }

    /**
     * Set isPrime2
     *
     * @param boolean $isPrime2
     * @return tpeParameters
     */
    public function setIsPrime2($isPrime2)
    {
        $this->isPrime2 = $isPrime2;

        return $this;
    }

    /**
     * Get isPrime2
     *
     * @return boolean 
     */
    public function getIsPrime2()
    {
        return $this->isPrime2;
    }

    /**
     * Set typePrime2
     *
     * @param string $typePrime2
     * @return tpeParameters
     */
    public function setTypePrime2($typePrime2)
    {
        $this->typePrime2 = $typePrime2;

        return $this;
    }

    /**
     * Get typePrime2
     *
     * @return string 
     */
    public function getTypePrime2()
    {
        return $this->typePrime2;
    }

    /**
     * Set amountPrime2
     *
     * @param integer $amountPrime2
     * @return tpeParameters
     */
    public function setAmountPrime2($amountPrime2)
    {
        $this->amountPrime2 = $amountPrime2;

        return $this;
    }

    /**
     * Get amountPrime2
     *
     * @return integer 
     */
    public function getAmountPrime2()
    {
        return $this->amountPrime2;
    }

    /**
     * Set isPrime3
     *
     * @param boolean $isPrime3
     * @return tpeParameters
     */
    public function setIsPrime3($isPrime3)
    {
        $this->isPrime3 = $isPrime3;

        return $this;
    }

    /**
     * Get isPrime3
     *
     * @return boolean 
     */
    public function getIsPrime3()
    {
        return $this->isPrime3;
    }

    /**
     * Set typePrime3
     *
     * @param string $typePrime3
     * @return tpeParameters
     */
    public function setTypePrime3($typePrime3)
    {
        $this->typePrime3 = $typePrime3;

        return $this;
    }

    /**
     * Get typePrime3
     *
     * @return string 
     */
    public function getTypePrime3()
    {
        return $this->typePrime3;
    }

    /**
     * Set amountPrime3
     *
     * @param integer $amountPrime3
     * @return tpeParameters
     */
    public function setAmountPrime3($amountPrime3)
    {
        $this->amountPrime3 = $amountPrime3;

        return $this;
    }

    /**
     * Get amountPrime3
     *
     * @return integer 
     */
    public function getAmountPrime3()
    {
        return $this->amountPrime3;
    }

    /**
     * Set isPrime4
     *
     * @param boolean $isPrime4
     * @return tpeParameters
     */
    public function setIsPrime4($isPrime4)
    {
        $this->isPrime4 = $isPrime4;

        return $this;
    }

    /**
     * Get isPrime4
     *
     * @return boolean 
     */
    public function getIsPrime4()
    {
        return $this->isPrime4;
    }

    /**
     * Set typePrime4
     *
     * @param string $typePrime4
     * @return tpeParameters
     */
    public function setTypePrime4($typePrime4)
    {
        $this->typePrime4 = $typePrime4;

        return $this;
    }

    /**
     * Get typePrime4
     *
     * @return string 
     */
    public function getTypePrime4()
    {
        return $this->typePrime4;
    }

    /**
     * Set amountPrime4
     *
     * @param integer $amountPrime4
     * @return tpeParameters
     */
    public function setAmountPrime4($amountPrime4)
    {
        $this->amountPrime4 = $amountPrime4;

        return $this;
    }

    /**
     * Get amountPrime4
     *
     * @return integer 
     */
    public function getAmountPrime4()
    {
        return $this->amountPrime4;
    }

    /**
     * Set isPrime5
     *
     * @param boolean $isPrime5
     * @return tpeParameters
     */
    public function setIsPrime5($isPrime5)
    {
        $this->isPrime5 = $isPrime5;

        return $this;
    }

    /**
     * Get isPrime5
     *
     * @return boolean 
     */
    public function getIsPrime5()
    {
        return $this->isPrime5;
    }

    /**
     * Set typePrime5
     *
     * @param string $typePrime5
     * @return tpeParameters
     */
    public function setTypePrime5($typePrime5)
    {
        $this->typePrime5 = $typePrime5;

        return $this;
    }

    /**
     * Get typePrime5
     *
     * @return string 
     */
    public function getTypePrime5()
    {
        return $this->typePrime5;
    }

    /**
     * Set amountPrime5
     *
     * @param integer $amountPrime5
     * @return tpeParameters
     */
    public function setAmountPrime5($amountPrime5)
    {
        $this->amountPrime5 = $amountPrime5;

        return $this;
    }

    /**
     * Get amountPrime5
     *
     * @return integer 
     */
    public function getAmountPrime5()
    {
        return $this->amountPrime5;
    }

    public function __construct()
    {
        $this->isPme1 = true;
        $this->isPme1Unit = false;
        $this->isPme2 = false;
        $this->isPme2Unit = false;
        $this->isPme2 = false;
        $this->isPme2Unit = false;
        $this->isPme3 = false;
        $this->isPme3Unit = false;
        $this->isPme1 = false;
        $this->isPme1Unit = false;
        $this->isPme5 = false;
        $this->isPme5Unit= true;
    }

    /**
     * Set levelPrime1
     *
     * @param integer $levelPrime1
     * @return tpeParameters
     */
    public function setLevelPrime1($levelPrime1)
    {
        $this->levelPrime1 = $levelPrime1;

        return $this;
    }

    /**
     * Get levelPrime1
     *
     * @return integer 
     */
    public function getLevelPrime1()
    {
        return $this->levelPrime1;
    }

    /**
     * Set levelPrime2
     *
     * @param integer $levelPrime2
     * @return tpeParameters
     */
    public function setLevelPrime2($levelPrime2)
    {
        $this->levelPrime2 = $levelPrime2;

        return $this;
    }

    /**
     * Get levelPrime2
     *
     * @return integer 
     */
    public function getLevelPrime2()
    {
        return $this->levelPrime2;
    }

    /**
     * Set levelPrime3
     *
     * @param integer $levelPrime3
     * @return tpeParameters
     */
    public function setLevelPrime3($levelPrime3)
    {
        $this->levelPrime3 = $levelPrime3;

        return $this;
    }

    /**
     * Get levelPrime3
     *
     * @return integer 
     */
    public function getLevelPrime3()
    {
        return $this->levelPrime3;
    }

    /**
     * Set levelPrime4
     *
     * @param integer $levelPrime4
     * @return tpeParameters
     */
    public function setLevelPrime4($levelPrime4)
    {
        $this->levelPrime4 = $levelPrime4;

        return $this;
    }

    /**
     * Get levelPrime4
     *
     * @return integer 
     */
    public function getLevelPrime4()
    {
        return $this->levelPrime4;
    }

    /**
     * Set levelPrime5
     *
     * @param integer $levelPrime5
     * @return tpeParameters
     */
    public function setLevelPrime5($levelPrime5)
    {
        $this->levelPrime5 = $levelPrime5;

        return $this;
    }

    /**
     * Get levelPrime5
     *
     * @return integer 
     */
    public function getLevelPrime5()
    {
        return $this->levelPrime5;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'parameters';
    }

    public function createParameterFile(Tpe $tpe)
    {


        if(!is_dir($this->getUploadDir()."/".$tpe->getTpeNumber()))
            mkdir($this->getUploadDir()."/".$tpe->getTpeNumber(),0777,true);

        if(file_exists($this->getUploadDir().'/'.$tpe->getTpeNumber().'/PARAM_'.$tpe->getTpeNumber().'.PAR'))
            unlink($this->getUploadDir().'/'.$tpe->getTpeNumber().'/PARAM_'.$tpe->getTpeNumber().'.PAR');

        $parameterFile = fopen($this->getUploadDir().'/'.$tpe->getTpeNumber().'/PARAM_'.$tpe->getTpeNumber().'.PAR','a+');
        fwrite($parameterFile,"F;".$this->getFtpHost().";".$this->getFtpPort().";".$this->getFtpLogin().";".$this->getFtpPassword().";".$this->getFtpMode().";".$this->getTypeConnexion()."\n");
        fwrite($parameterFile,"G;".$this->getApnGprs().";".$this->getLoginGprs().";".$this->getPasswordGprs()."\n");

        if($this->getIsPme1())
        {
            fwrite($parameterFile,"1;".$this->getIsPme1Unit().";".($this->getMinPurchase()*100));
            if($this->getIsPrime1())
                fwrite($parameterFile,";1;".($this->getLevelPrime1()*100).";".$this->getTypePrime1().";".($this->getAmountPrime1()*100)."\r\n");
            else
                fwrite($parameterFile,";0\n");
        }
        if($this->getIsPme2())
        {
            fwrite($parameterFile,"2;".$this->getIsPme2Unit().";".($this->getMinPurchase()*100));
            if($this->getIsPrime2())
                fwrite($parameterFile,";1;".($this->getLevelPrime2()*100).";".$this->getTypePrime2().";".($this->getAmountPrime2()*100)."\n");
            else
                fwrite($parameterFile,";0\n");
        }
        if($this->getIsPme3())
        {
            fwrite($parameterFile,"3;".$this->getIsPme3Unit().";".($this->getMinPurchase()*100));
            if($this->getIsPrime3())
                fwrite($parameterFile,";1;".($this->getLevelPrime3()*100).";".$this->getTypePrime3().";".($this->getAmountPrime3()*100)."\n");
            else
                fwrite($parameterFile,";0\n");
        }
        if($this->getIsPme4())
        {
            fwrite($parameterFile,"4;".$this->getIsPme4Unit().";".($this->getMinPurchase()*100));
            if($this->getIsPrime4())
                fwrite($parameterFile,";1;".($this->getLevelPrime4()*100).";".$this->getTypePrime4().";".($this->getAmountPrime4()*100)."\n");
            else
                fwrite($parameterFile,";0\n");
        }
        if($this->getIsPme5())
        {
            fwrite($parameterFile,"5;".$this->getIsPme5Unit().";".($this->getMinPurchase()*100));
            if($this->getIsPrime5())
                fwrite($parameterFile,";1;".($this->getLevelPrime5()*100).";".$this->getTypePrime5().";".($this->getAmountPrime5()*100)."\n");
            else
                fwrite($parameterFile,";0\n");
        }
        fwrite($parameterFile,"E;".$this->getHeader1().";".$this->getHeader2().";".$this->getHeader3().";".$this->getHeader4().";".$this->getHeader5()."\n");
        fwrite($parameterFile,"P;".$this->getFooter1().";".$this->getFooter2().";".$this->getFooter3().";".$this->getFooter4().";".$this->getFooter5()."\n");
        fwrite($parameterFile,"S;".$this->getUrlSoap().";".$this->getPortSoap()."\n");
        fclose($parameterFile);

        $this->fileName = $this->getUploadDir().'/'.$tpe->getTpeNumber().'/PARAM_'.$tpe->getTpeNumber().'.PAR';
    }

    /**
     * Set urlSoap
     *
     * @param string $urlSoap
     * @return tpeParameters
     */
    public function setUrlSoap($urlSoap)
    {
        $this->urlSoap = $urlSoap;

        return $this;
    }

    /**
     * Get urlSoap
     *
     * @return string 
     */
    public function getUrlSoap()
    {
        return $this->urlSoap;
    }

    /**
     * Set portSoap
     *
     * @param string $portSoap
     * @return tpeParameters
     */
    public function setPortSoap($portSoap)
    {
        $this->portSoap = $portSoap;

        return $this;
    }

    /**
     * Get portSoap
     *
     * @return string 
     */
    public function getPortSoap()
    {
        return $this->portSoap;
    }



    /**
     * Set header1
     *
     * @param string $header1
     * @return tpeParameters
     */
    public function setHeader1($header1)
    {
        $this->header1 = $header1;

        return $this;
    }

    /**
     * Get header1
     *
     * @return string 
     */
    public function getHeader1()
    {
        return $this->header1;
    }

    /**
     * Set header2
     *
     * @param string $header2
     * @return tpeParameters
     */
    public function setHeader2($header2)
    {
        $this->header2 = $header2;

        return $this;
    }

    /**
     * Get header2
     *
     * @return string 
     */
    public function getHeader2()
    {
        return $this->header2;
    }

    /**
     * Set header3
     *
     * @param string $header3
     * @return tpeParameters
     */
    public function setHeader3($header3)
    {
        $this->header3 = $header3;

        return $this;
    }

    /**
     * Get header3
     *
     * @return string 
     */
    public function getHeader3()
    {
        return $this->header3;
    }

    /**
     * Set header4
     *
     * @param string $header4
     * @return tpeParameters
     */
    public function setHeader4($header4)
    {
        $this->header4 = $header4;

        return $this;
    }

    /**
     * Get header4
     *
     * @return string 
     */
    public function getHeader4()
    {
        return $this->header4;
    }

    /**
     * Set header5
     *
     * @param string $header5
     * @return tpeParameters
     */
    public function setHeader5($header5)
    {
        $this->header5 = $header5;

        return $this;
    }

    /**
     * Get header5
     *
     * @return string 
     */
    public function getHeader5()
    {
        return $this->header5;
    }

    /**
     * Set footer1
     *
     * @param string $footer1
     * @return tpeParameters
     */
    public function setFooter1($footer1)
    {
        $this->footer1 = $footer1;

        return $this;
    }

    /**
     * Get footer1
     *
     * @return string 
     */
    public function getFooter1()
    {
        return $this->footer1;
    }

    /**
     * Set footer2
     *
     * @param string $footer2
     * @return tpeParameters
     */
    public function setFooter2($footer2)
    {
        $this->footer2 = $footer2;

        return $this;
    }

    /**
     * Get footer2
     *
     * @return string 
     */
    public function getFooter2()
    {
        return $this->footer2;
    }

    /**
     * Set footer3
     *
     * @param string $footer3
     * @return tpeParameters
     */
    public function setFooter3($footer3)
    {
        $this->footer3 = $footer3;

        return $this;
    }

    /**
     * Get footer3
     *
     * @return string 
     */
    public function getFooter3()
    {
        return $this->footer3;
    }

    /**
     * Set footer4
     *
     * @param string $footer4
     * @return tpeParameters
     */
    public function setFooter4($footer4)
    {
        $this->footer4 = $footer4;

        return $this;
    }

    /**
     * Get footer4
     *
     * @return string 
     */
    public function getFooter4()
    {
        return $this->footer4;
    }

    /**
     * Set footer5
     *
     * @param string $footer5
     * @return tpeParameters
     */
    public function setFooter5($footer5)
    {
        $this->footer5 = $footer5;

        return $this;
    }

    /**
     * Get footer5
     *
     * @return string 
     */
    public function getFooter5()
    {
        return $this->footer5;
    }

    /**
     * Set minPurchase
     *
     * @param integer $minPurchase
     * @return tpeParameters
     */
    public function setMinPurchase($minPurchase)
    {
        $this->minPurchase = $minPurchase;

        return $this;
    }

    /**
     * Get minPurchase
     *
     * @return integer 
     */
    public function getMinPurchase()
    {
        return $this->minPurchase;
    }

    /**
     * Set typeConnexion
     *
     * @param string $typeConnexion
     * @return tpeParameters
     */
    public function setTypeConnexion($typeConnexion)
    {
        $this->typeConnexion = $typeConnexion;

        return $this;
    }

    /**
     * Get typeConnexion
     *
     * @return string 
     */
    public function getTypeConnexion()
    {
        return $this->typeConnexion;
    }
}
