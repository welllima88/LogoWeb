<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 13/06/14
 * Time: 15:49
 */

namespace Cib\Bundle\CustomerBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="cib_client")
 */
class Client
{
    /**
     * @var
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $clientId;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $clientNumber;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\Length(min=4,max=50,minMessage="Le nom du client doit être composé d'au moins 4 caractères",maxMessage="Le nom du client ne peut pas dépasser 50 caractères")
     */
    private $clientName;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\Length(min=4,max=50,minMessage="Le prénom du client doit être composé d'au moins 4 caractères",maxMessage="Le prénom du client ne peut pas dépasser 50 caractères")
     */
    private $clientFirstName;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    private $clientGender;

    /**
     * @var
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $clientBirthDate;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min=4,max=50,minMessage="L'adresse du client doit être composé d'au moins 4 caractères",maxMessage="L'adresse du client ne peut pas dépasser 50 caractères")
     */
    private $clientAddress;

    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min=5,max=5,minMessage="Le code postal doit être composé de 5 chiffres",maxMessage="Le code postal doit être composé de 5 chiffres")
     * @Assert\Regex(pattern="/^[0-9]{5}$/",message="Le code postal doit être composé de cinq chiffres")
     */
    private $clientZipCode;


    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $clientCity;

    /**
     * @var
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min=10,max=15,minMessage="Le numéro de téléphone doit-être composé d'au moins 10 caractères",maxMessage="Le numéro de téléphone ne peut pas excéder 15 caractères")
     * @Assert\Regex(pattern="/^\d{10}$/", message="Le numéro de téléphone doit-être composé de chiffre uniquement")
     */
    private $homePhone;


    /**
     * @var
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min=10,max=15,minMessage="Le numéro de téléphone doit-être composé d'au moins 10 caractères",maxMessage="Le numéro de téléphone ne peut pas excéder 15 caractères")
     * @Assert\Regex(pattern="/^\d{10}$/", message="Le numéro de téléphone doit-être composé de chiffre uniquement")
     */
    private $cellPhone;


    /**
     * @var
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min=10,max=15,minMessage="Le numéro de téléphone doit-être composé d'au moins 10 caractères",maxMessage="Le numéro de téléphone ne peut pas excéder 15 caractères")
     * @Assert\Regex(pattern="/^\d{10}$/", message="Le numéro de téléphone doit-être composé de chiffre uniquement")
     */
    private $officePhone;


    /**
     * @var
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $mailAddress;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\Regex("/^\d{1,3}$/")
     * @Assert\Length(max="3")
     */
    private $age;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picturePath;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $pictureFile;

    private $token;


    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
    public function getAbsolutePath()
    {
        return null === $this->picturePath
            ? null
            : $this->getUploadRootDir().'/'.$this->picturePath;
    }

    public function getWebPath()
    {
        return null === $this->picturePath
            ? null
            : $this->getUploadDir().'/'.$this->picturePath;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }


    /**
     * Sets file.
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $pictureFile
     * @internal param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setPictureFile(UploadedFile $pictureFile = null)
    {
        $this->pictureFile = $pictureFile;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getPictureFile()
    {
        return $this->pictureFile;
    }

    /**
     * Get clientId
     *
     * @return integer 
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set clientNumber
     *
     * @param string $clientNumber
     * @return Client
     */
    public function setClientNumber($clientNumber)
    {
        $this->clientNumber = $clientNumber;

        return $this;
    }

    /**
     * Get clientNumber
     *
     * @return string 
     */
    public function getClientNumber()
    {
        return $this->clientNumber;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     * @return Client
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string 
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set clientFirstName
     *
     * @param string $clientFirstName
     * @return Client
     */
    public function setClientFirstName($clientFirstName)
    {
        $this->clientFirstName = $clientFirstName;

        return $this;
    }

    /**
     * Get clientFirstName
     *
     * @return string 
     */
    public function getClientFirstName()
    {
        return $this->clientFirstName;
    }

    /**
     * Set clientGender
     *
     * @param string $clientGender
     * @return Client
     */
    public function setClientGender($clientGender)
    {
        $this->clientGender = $clientGender;

        return $this;
    }

    /**
     * Get clientGender
     *
     * @return string 
     */
    public function getClientGender()
    {
        return $this->clientGender;
    }

    /**
     * Set clientBirthDate
     *
     * @param \DateTime $clientBirthDate
     * @return Client
     */
    public function setClientBirthDate($clientBirthDate)
    {
        $this->clientBirthDate = $clientBirthDate;

        return $this;
    }

    /**
     * Get clientBirthDate
     *
     * @return \DateTime 
     */
    public function getClientBirthDate()
    {
        return $this->clientBirthDate;
    }

    /**
     * Set clientAddress
     *
     * @param string $clientAddress
     * @return Client
     */
    public function setClientAddress($clientAddress)
    {
        $this->clientAddress = $clientAddress;

        return $this;
    }

    /**
     * Get clientAddress
     *
     * @return string 
     */
    public function getClientAddress()
    {
        return $this->clientAddress;
    }

    /**
     * Set clientZipCode
     *
     * @param string $clientZipCode
     * @return Client
     */
    public function setClientZipCode($clientZipCode)
    {
        $this->clientZipCode = $clientZipCode;

        return $this;
    }

    /**
     * Get clientZipCode
     *
     * @return string 
     */
    public function getClientZipCode()
    {
        return $this->clientZipCode;
    }

    /**
     * Set clientCity
     *
     * @param string $clientCity
     * @return Client
     */
    public function setClientCity($clientCity)
    {
        $this->clientCity = $clientCity;

        return $this;
    }

    /**
     * Get clientCity
     *
     * @return string 
     */
    public function getClientCity()
    {
        return $this->clientCity;
    }

    /**
     * Set homePhone
     *
     * @param string $homePhone
     * @return Client
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;

        return $this;
    }

    /**
     * Get homePhone
     *
     * @return string 
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set cellPhone
     *
     * @param string $cellPhone
     * @return Client
     */
    public function setCellPhone($cellPhone)
    {
        $this->cellPhone = $cellPhone;

        return $this;
    }

    /**
     * Get cellPhone
     *
     * @return string 
     */
    public function getCellPhone()
    {
        return $this->cellPhone;
    }

    /**
     * Set officePhone
     *
     * @param string $officePhone
     * @return Client
     */
    public function setOfficePhone($officePhone)
    {
        $this->officePhone = $officePhone;

        return $this;
    }

    /**
     * Get officePhone
     *
     * @return string 
     */
    public function getOfficePhone()
    {
        return $this->officePhone;
    }

    /**
     * Set mailAddress
     *
     * @param string $mailAddress
     * @return Client
     */
    public function setMailAddress($mailAddress)
    {
        $this->mailAddress = $mailAddress;

        return $this;
    }

    /**
     * Get mailAddress
     *
     * @return string 
     */
    public function getMailAddress()
    {
        return $this->mailAddress;
    }

    /**
     * Set age
     *
     * @internal param string $age
     * @return Client
     */
    public function setAge()
    {
        $date = new \DateTime();
        $tempdate = new \DateTime($this->clientBirthDate->format('Y-m-d'));
        $diff = $tempdate->diff($date);
        $this->age = $diff->y;
        return $this;
    }

    /**
     * Get age
     *
     * @return string 
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set pictureName
     *
     * @param string $pictureName
     * @return Client
     */
    public function setPictureName($pictureName)
    {
        $this->pictureName = $pictureName;

        return $this;
    }

    /**
     * Get pictureName
     *
     * @return string 
     */
    public function getPictureName()
    {
        return $this->pictureName;
    }

    /**
     * Set picturePath
     *
     * @param string $picturePath
     * @return Client
     */
    public function setPicturePath($picturePath)
    {
        $this->picturePath = $picturePath;

        return $this;
    }

    /**
     * Get picturePath
     *
     * @return string 
     */
    public function getPicturePath()
    {
        return $this->picturePath;
    }


    public function upload()
    {
        // la propriété « file » peut être vide si le champ n'est pas requis
        if (null === $this->pictureFile) {
            return;
        }

        // utilisez le nom de fichier original ici mais
        // vous devriez « l'assainir » pour au moins éviter
        // quelconques problèmes de sécurité

        // la méthode « move » prend comme arguments le répertoire cible et
        // le nom de fichier cible où le fichier doit être déplacé
//        var_dump($this->getUploadRootDir());die;
        if(!is_dir($this->getUploadRootDir().'/'.utf8_decode($this->getClientName()).'/'.utf8_decode($this->getClientFirstName())))
            mkdir($this->getUploadRootDir().'/'.utf8_decode($this->getClientName()).'/'.utf8_decode($this->getClientFirstName()),0777,true);

        $this->pictureFile->move($this->getUploadRootDir().'/'.utf8_decode($this->getClientName()).'/'.utf8_decode($this->getClientFirstName()), utf8_decode($this->getClientName()).'/'.utf8_decode($this->getClientFirstName()).'/'.$this->pictureFile->getClientOriginalName());

        // définit la propriété « path » comme étant le nom de fichier où vous
        // avez stocké le fichier
        $this->picturePath = $this->getClientName().'/'.$this->getClientFirstName().'/'.$this->pictureFile->getClientOriginalName();


        $this->pictureName = $this->pictureFile->getClientOriginalName();
        //var_dump($this);die;
        // « nettoie » la propriété « file » comme vous n'en aurez plus besoin
        $this->pictureFile = null;
    }

//    public function setAge($birthDate)
//    {
//
//    }
}
