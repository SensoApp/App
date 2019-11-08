<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-09
 * Time: 21:22
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Phone
 * @package App\Entity
 * @ORM\Entity()
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     */
    private $phonenumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="phone")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactEndClient", inversedBy="phone")
     */
    private $clientcontact;

    /**
     * @var \DateTime
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
    public function getPhonenumber()
    {
        return $this->phonenumber;
    }

    /**
     * @param mixed $phonenumber
     */
    public function setPhonenumber($phonenumber)
    {
        $this->phonenumber = $phonenumber;
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
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

    public function __toString()
    {
        // TODO: Implement __toString() method.

        return (string) $this->getPhonenumber();
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

}
