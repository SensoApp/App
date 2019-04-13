<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-04
 * Time: 22:02
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="contact")
 */
class Contact
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="firstname")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mail", mappedBy="contact")
     */
    private $mail;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Phone", mappedBy="contact")
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true )
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->mail = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }


    /**
     * Add mail
     *
     * @param \AppBundle\Entity\Mail $mail
     *
     * @return Contact
     */
    public function addMail(\AppBundle\Entity\Mail $mail)
    {
        $this->mail[] = $mail;

        return $this;
    }

    /**
     * Remove mail
     *
     * @param \AppBundle\Entity\Mail $mail
     */
    public function removeMail(\AppBundle\Entity\Mail $mail)
    {
        $this->mail->removeElement($mail);
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Add phone
     *
     * @param \AppBundle\Entity\Phone $phone
     *
     * @return Contact
     */
    public function addPhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phone[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \AppBundle\Entity\Phone $phone
     */
    public function removePhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phone->removeElement($phone);
    }
}
