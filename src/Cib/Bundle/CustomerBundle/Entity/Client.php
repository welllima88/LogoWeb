<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 13/06/14
 * Time: 15:49
 */

namespace Cib\Bundle\CustomerBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
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
     */
    private $clientNumber;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     * @Assert\Length(min=4,min=50,minMessage="Le nom du client doit être composé d'au moins 4 caractères",)
     */
    private $clientName;

    /**
     * @var
     *
     * @ORM\Column(type="string")
     */
    private $clientFirstName;

    private $clientGender;

    private $clientBirthDate;

    private $clientAddress;

    private $clientZipCode;

    private $clientCity;

    private $homePhone;

    private $cellPhone;

    private $officePhone;

    private $mailAddress;

    private $age;

    private $token;



} 