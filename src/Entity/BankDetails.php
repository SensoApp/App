<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BankDetails
 *
 * @ORM\Table(name="bank_details")
 * @ORM\Entity(repositoryClass="App\Repository\BankDetailsRepository")
 *
 */
class BankDetails
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=255)
     * @Assert\Iban( message="This is not a valid Iban")
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="biccode", type="string", length=255)
     * @Assert\Bic( message="This is not a valid Bic code")
     * @Assert\NotBlank()
     */
    private $biccode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="bankdetails")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Iban( message="This is not a valid Iban")
     */
    private $ibanstatement;


    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set iban.
     *
     * @param string $iban
     *
     * @return BankDetails
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban.
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set biccode.
     *
     * @param string $biccode
     *
     * @return BankDetails
     */
    public function setBiccode($biccode)
    {
        $this->biccode = $biccode;

        return $this;
    }

    /**
     * Get biccode.
     *
     * @return string
     */
    public function getBiccode()
    {
        return $this->biccode;
    }

    /**
     * Set contact.
     *
     * @param \App\Entity\Contact|null $contact
     *
     * @return BankDetails
     */
    public function setContact(\App\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact.
     *
     * @return \App\Entity\Contact|null
     */
    public function getContact()
    {
        return $this->contact;
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

    public function __toString()
    {
        // TODO: Implement __toString() method.

        return (string) $this->getContact();
    }

    public function getIbanstatement()
    {
        return $this->ibanstatement;
    }

    public function setIbanstatement($ibanstatement)
    {
        $this->ibanstatement = $ibanstatement;

        return $this;
    }
}
