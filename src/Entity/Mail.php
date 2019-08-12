<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-04
 * Time: 22:03
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Mail
 * @ORM\Entity()
 * @ORM\Table(name="mail")
 *
 */
class Mail
{

     /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="mail", type="string")
     * @Assert\Email(message="The email '{{value}}' is not valid ", checkMX=true)
     * @Assert\NotBlank()
     */
    private $mail;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="mail")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactEndClient", inversedBy="clientcontact")
     */
    private $clientcontact;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
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
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
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

    public function getClientcontact(): ?ContactEndClient
    {
        return $this->clientcontact;
    }

    public function setClientcontact(?ContactEndClient $clientcontact): self
    {
        $this->clientcontact = $clientcontact;

        return $this;
    }

    /*public function __toString()
    {

        return (string) $this->getMail();
    }*/

}
