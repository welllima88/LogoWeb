<?php
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 11/06/14
 * Time: 09:19
 */

namespace Cib\Bundle\UserBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields="username", message="Cet utilisateur existe déjà")
 * @UniqueEntity(fields="email", message="Adresse mail dèjà utilisée")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
