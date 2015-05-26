<?php

namespace Cib\Bundle\CustomerBundle\Entity;

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
    private $logoLowerTicket;

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
    private $pathLow;

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
     * @ORM\ManyToOne(targetEntity="Cib\Bundle\ActivityBundle\Entity\Tpe",cascade={"persist"}, inversedBy="logos")
     * @ORM\JoinColumn(name="tpeId", referencedColumnName="tpeId", onDelete="SET NULL")
     */
    private $tpes;


    public function getTpes()
    {
        return $this->tpes;
    }

    public function setTpes($tpes)
    {
        $this->tpes = $tpes;

        return $this;
    }

    public function setLogoTopTicket($logoTopTicket = null)
    {
        $this->logoTopTicket = $logoTopTicket;

        return $this;
    }

    public function setLogoLowerTicket($logoLowerTicket = null)
    {
        $this->logoLowerTicket = $logoLowerTicket;

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

    public function getLogoLowerTicket()
    {
        return $this->logoLowerTicket;
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

    public function getAbsolutePathLow()
    {
        return null === $this->pathLow ? null : $this->getUploadRootDir().'/'.$this->pathLow;
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

    public function getWebPathLow()
    {
        return null === $this->pathLow ? null : $this->getUploadDir().'/'.$this->pathLow;
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
        return 'parameters/'.$this->tpes->getTpeNumber();
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
        if (null !== $this->logoLowerTicket) {
            // faites ce que vous voulez pour générer un nom unique
            $this->pathLow = sha1(uniqid(mt_rand(), true)).'.'.$this->logoLowerTicket->getClientOriginalExtension();
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
        if ($this->pathLow == $this->getAbsolutePathLow()) {
            $this->pathLow = null;
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
            return;
        }
        if (null === $this->logoLowerTicket) {
            return;
        }
        if (null === $this->logoWallpaper) {
            return;
        }

        // utilisez le nom de fichier original ici mais
        // vous devriez « l'assainir » pour au moins éviter
        // quelconques problèmes de sécurité

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
        $this->logoTopTicket->move($this->getUploadRootDir(), $this->logoTopTicket->getClientOriginalName());
        $this->logoWallpaper->move($this->getUploadRootDir(), $this->logoWallpaper->getClientOriginalName());
        $this->logoLowerTicket->move($this->getUploadRootDir(), $this->logoLowerTicket->getClientOriginalName());


        // définit la propriété « path » comme étant le nom de fichier où vous
        // avez stocké le fichier
        $this->pathTop = $this->logoTopTicket->getClientOriginalName();
        $this->pathLow = $this->logoLowerTicket->getClientOriginalName();
        $this->pathWallpaper = $this->logoWallpaper->getClientOriginalName();

        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->logoTopTicket = null;
        $this->logoLowerTicket = null;
        $this->logoWallpaper = null;
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
     * Set pathLow
     *
     * @param string $pathLow
     *
     * @return Logo
     */
    public function setPathLow($pathLow)
    {
        $this->pathLow = $pathLow;

        return $this;
    }

    /**
     * Get pathLow
     *
     * @return string
     */
    public function getPathLow()
    {
        return $this->pathLow;
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
}
