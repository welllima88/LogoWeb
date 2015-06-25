<?php

namespace Cib\Bundle\CustomerBundle\Entity;

use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Logo
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="cib_logo")
 * @ORM\Entity(repositoryClass="Cib\Bundle\CustomerBundle\Entity\logoRepository")
 */
class Logo
{
    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $logoId;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logoName;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $logoTopTicket;


    /**
     * @Assert\File(maxSize="6000000")
     */
    private $logoWallpaper;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $pathTop;

    /**
     * @var
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $logoGoal;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $pathWallpaper;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $logoTypeTPE;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyName;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyAddress;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyTel;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyWebAddr;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyCity;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $societyCp;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Tpe",cascade={"persist"})
     * @ORM\JoinColumn(name="tpeId", referencedColumnName="tpeId", onDelete="SET NULL")
     */
    private $tpe;


    public function getTpe()
    {
        return $this->tpe;
    }

    public function setTpe($tpe)
    {
        $this->tpe = $tpe;

        return $this;
    }

    public function setLogoTopTicket($logoTopTicket = null)
    {
        $this->logoTopTicket = $logoTopTicket;

        return $this;
    }

    public function setLogoWallPaper($logoWallpaper = null)
    {
        $this->logoWallpaper = $logoWallpaper;

        return $this;
    }

    /**
     * Get idLogo
     *
     * @return integer
     */
    public function getLogoId()
    {
        return $this->logoId;
    }

    public function getLogoName()
    {
        return $this->logoName;
    }

    public function getLogoTopTicket()
    {
        return $this->logoTopTicket;
    }


    public function getLogoWallpaper()
    {
        return $this->logoWallpaper;
    }

    public function getLogoTypeTPE()
    {
        return $this->logoTypeTPE;
    }

    public function getSocietyName()
    {
        return $this->societyName;
    }

    public function getSocietyAddress()
    {
        return $this->societyAddress;
    }

    public function getSocietyTel()
    {
        return $this->societyTel;
    }

    public function getSocietyWebAddr()
    {
        return $this->societyWebAddr;
    }

    public function getSocietyCity()
    {
        return $this->societyCity;
    }

    public function getSocietyCp()
    {
        return $this->societyCp;
    }

    public function setLogoName($logoName)
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function setLogoTypeTPE($logoTypeTPE)
    {
        $this->logoTypeTPE = $logoTypeTPE;

        return $this;
    }

    public function setSocietyName($societyName)
    {
        $this->societyName = $societyName;

        return $this;
    }

    public function setSocietyAddress($societyAddress)
    {
        $this->societyAddress = $societyAddress;

        return $this;
    }

    public function setSocietyWebAddr($societyWebAddr)
    {
        $this->societyWebAddr = $societyWebAddr;

        return $this;
    }

    public function setSocietyCity($societyCity)
    {
        $this->societyCity = $societyCity;

        return $this;
    }

    public function setSocietyTel($societyTel)
    {
        $this->societyTel = $societyTel;

        return $this;
    }
    public function setSocietyCp($societyCp)
    {
        $this->societyCp = $societyCp;

        return $this;
    }

    public function getAbsolutePathTop()
    {
        return null === $this->pathEntete ? null : $this->getUploadRootDir().'/'.$this->pathEntete;
    }


    public function getAbsolutePathWallpaper()
    {
        return null === $this->pathWallpaper ? null : $this->getUploadRootDir().'/'.$this->pathWallpaper;
    }

    public function getWebPathTop()
    {
        return null === $this->pathTop ? null : $this->getUploadDir().'/'.$this->pathTop;
    }

    public function getWebPathWallpaper()
    {
        return null === $this->pathWallpaper ? null : $this->getUploadDir().'/'.$this->pathWallpaper;
    }


    public function getPathSrc()
    {
        return $this->getUploadDir();
    }
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }


    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'parameters/'.$this->tpe->getTpeNumber();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->logoTopTicket) {
            // faites ce que vous voulez pour générer un nom unique
            $this->pathTop = sha1(uniqid(mt_rand(), true)).'.'.$this->logoTopTicket->getClientOriginalExtension();
        }
        if (null !== $this->logoWallpaper) {
            // faites ce que vous voulez pour générer un nom unique
            $this->pathWallpaper = sha1(uniqid(mt_rand(), true)).'.'.$this->logoWallpaper->getClientOriginalExtension();
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($this->pathTop == $this->getAbsolutePathTop()) {
            $this->pathTop = null;
        }
        if ($this->pathWallpaper == $this->getAbsolutePathWallpaper()) {
            $this->pathWallpaper = null;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {

        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->logoTopTicket) {
            $this->logoTopTicket = null;
        }
        else{
            $this->logoTopTicket->move($this->getUploadRootDir(), $this->logoTopTicket->getClientOriginalName());
            $this->pathTop = $this->logoTopTicket->getClientOriginalName();
            $this->logoTopTicket = null;
        }
        if (null === $this->logoWallpaper) {
            $this->logoWallpaper = null;
        }
        else {
            $this->logoWallpaper->move($this->getUploadRootDir(), $this->logoWallpaper->getClientOriginalName());
            $this->pathWallpaper = $this->logoWallpaper->getClientOriginalName();
            $this->logoWallpaper = null;
        }

        // utilisez le nom de fichier original ici mais
        // vous devriez « l'assainir » pour au moins éviter
        // quelconques problèmes de sécurité

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé

        // définit la propriété « path » comme étant le nom de fichier où vous
        // avez stocké le fichier

        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
    }


    /**
     * Set pathTop
     *
     * @param string $pathTop
     *
     * @return Logo
     */
    public function setPathTop($pathTop)
    {
        $this->pathTop = $pathTop;

        return $this;
    }

    /**
     * Get pathTop
     *
     * @return string
     */
    public function getPathTop()
    {
        return $this->pathTop;
    }



    /**
     * Set pathWallpaper
     *
     * @param string $pathWallpaper
     *
     * @return Logo
     */
    public function setPathWallpaper($pathWallpaper)
    {
        $this->pathWallpaper = $pathWallpaper;

        return $this;
    }

    /**
     * Get pathWallpaper
     *
     * @return string
     */
    public function getPathWallpaper()
    {
        return $this->pathWallpaper;
    }


    /**
     * Set logoGoal
     *
     * @param boolean $logoGoal
     *
     * @return Logo
     */
    public function setLogoGoal($logoGoal)
    {
        $this->logoGoal = $logoGoal;

        return $this;
    }

    /**
     * Get logoGoal
     *
     * @return boolean
     */
    public function getLogoGoal()
    {
        return $this->logoGoal;
    }

    public function writeFileParam(Logo $logo, Ftp $ftp)
    {
        $fileParam = $logo->getPathSrc().'/PARAM_LOGO.PAR';
        $handle = fopen($fileParam, "w+");
        fwrite($handle, $logo->getSocietyName().";");
        fwrite($handle, $logo->getSocietyAddress().";");
        fwrite($handle, $logo->getSocietyTel().";");
        fwrite($handle, $logo->getSocietyWebAddr().";");
        fwrite($handle, $logo->getSocietyCity().";");
        fwrite($handle, $logo->getSocietyCp().";");
        fwrite($handle, $ftp->getFtpHost().";");
        fwrite($handle, $ftp->getFtpLogin().";");
        fwrite($handle, $ftp->getFtpPassword().";");
        fwrite($handle, $ftp->getFtpPort().";");
        fwrite($handle, $ftp->getFtpMode()."\n");
    }


}