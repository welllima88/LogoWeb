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
use Symfony\Component\Validator\Constraints as Assert;

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

    private $token;

    /**
     * @var
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Regex(pattern="/^[0-9]+$/")
     */
    private $rights;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Cib\Bundle\ActivityBundle\Entity\Signboard", mappedBy="user", cascade={"persist"})
     */
    private $signboards;

    private $isAdmin;

    public function isAdmin()
    {
        foreach($this->roles as $role){
            if($role == 'ROLE_ADMIN')
                return true;
        }
        return false;
    }

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

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set rights
     *
     * @param integer $rights
     *
     * @return User
     */
    public function setRights($rights)
    {
        $this->rights = $rights;

        return $this;
    }

    /**
     * Get rights
     *
     * @return integer
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * Add signboard
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Signboard $signboard
     *
     * @return User
     */
    public function addSignboard(\Cib\Bundle\ActivityBundle\Entity\Signboard $signboard)
    {
        $this->signboards[] = $signboard;

        return $this;
    }

    /**
     * Remove signboard
     *
     * @param \Cib\Bundle\ActivityBundle\Entity\Signboard $signboard
     */
    public function removeSignboard(\Cib\Bundle\ActivityBundle\Entity\Signboard $signboard)
    {
        $this->signboards->removeElement($signboard);
    }

    /**
     * Get signboards
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSignboards()
    {
        return $this->signboards;
    }
}
