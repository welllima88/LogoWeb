<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 12/06/14
 * Time: 12:03
 */

namespace Cib\Bundle\ActivityBundle\Entity;

use Cib\Bundle\FtpBundle\Entity\Ftp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cib_tpe")
 * @ORM\Entity(repositoryClass="Cib\Bundle\ActivityBundle\Entity\tpeRepository")
 * @UniqueEntity(fields="tpeNumber", message="un tpe portant ce numéro éxiste déjà")
 */
class Tpe
{
    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $tpeId;

    /**
     * @var
     *
     * @ORM\Column(type="text")
     * @Assert\Length(min=6,max=8,minMessage="Le numéro de tpe doit être composé de 6 caractères minimum",maxMessage="Le numéro de tpe ne peut pas excéder 8 caractères")
     * @Assert\Regex(pattern="/(^\d{6,8}$)/", message="le numéro de terminal est composé uniquement de 6 à 8 chiffres")
     * @Assert\NotBlank()
     */
    private $tpeNumber;

    /**
     * @var
     *
     * @ORM\OneToOne(targetEntity="tpeParameters", cascade="persist")
     * @ORM\JoinColumn(name="tpeParametersId", referencedColumnName="tpeParametersId")
     */
    private $tpeParameters;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Store", cascade={"persist"}, inversedBy="tpe")
     * @ORM\JoinColumn(name="storeId", referencedColumnName="storeId", onDelete="SET NULL")
     */
    private $store;


    private $token;

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
     return $this->token;
    }

    /**
     * Get tpeId
     *
     * @return integer 
     */
    public function getTpeId()
    {
        return $this->tpeId;
    }

    /**
     * Set tpeNumber
     *
     * @param string $tpeNumber
     * @return Tpe
     */
    public function setTpeNumber($tpeNumber)
    {
        $this->tpeNumber = $tpeNumber;

        return $this;
    }

    /**
     * Get tpeNumber
     *
     * @return string 
     */
    public function getTpeNumber()
    {
        return $this->tpeNumber;
    }

    /**
     * Set store
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Store $store
     * @return Tpe
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
     * Set tpeParameters
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\tpeParameters $tpeParameters
     * @return Tpe
     */
    public function setTpeParameters(\Cib\Bundle\ActivityBundle\Entity\tpeParameters $tpeParameters = null)
    {
        $this->tpeParameters = $tpeParameters;

        return $this;
    }

    /**
     * Get tpeParameters
     *
     * @return \Cib\Bundle\ActivityBundle\Entity\tpeParameters 
     */
    public function getTpeParameters()
    {
        return $this->tpeParameters;
    }

    public function uploadParameterFile(Ftp $ftp)
    {
        return $ftp->uploadParameterFile($this);
    }

    public function rmdir_recursive()
    {
        $dir = $this->getTpeParameters()->getUploadDir().'/'.$this->getTpeNumber();
        //Liste le contenu du répertoire dans un tableau
        $dir_content = @scandir($dir);
        //Est-ce bien un répertoire?
        if($dir_content !== FALSE){
            //Pour chaque entrée du répertoire
            foreach ($dir_content as $entry)
            {
                //Raccourcis symboliques sous Unix, on passe
                if(!in_array($entry, array('.','..'))){
                    //On retrouve le chemin par rapport au début
                    $entry = $dir . '/' . $entry;
                    //Cette entrée n'est pas un dossier: on l'efface
                    if(!is_dir($entry)){
                        unlink($entry);
                    }
                    //Cette entrée est un dossier, on recommence sur ce dossier
                    else{
                        rmdir_recursive($entry);
                    }
                }
            }
        }
        //On a bien effacé toutes les entrées du dossier, on peut à présent l'effacer
        return @rmdir($dir);
    }
}
