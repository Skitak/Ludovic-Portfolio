<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=75, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="text")
     */
    private $about;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $aboutTitle;


    public function __construct(){
    }

    public function getUsername(){
        return $this->username;
    }

    public function setUsername($user) {
        $this->username = $user;
    }

    public function getSalt(){
        // Not usefull with bcrypt
        return null;
    }

    public function getAbout(){
        return $this->about;
    }

    public function setAbout($text){
        $this->about = $text;
    }

    public function setAboutTitle($text){
        $this->aboutTitle = $text;
    }


    public function getAboutTitle(){
        return $this->aboutTitle;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($pass)
    {
        $this->password = $pass;
    }

    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }
}