<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BankDetails
 *
 * @ORM\Table(name="bank_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BankDetailsRepository")
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
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="biccode", type="string", length=255)
     */
    private $biccode;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="bankdetails")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;


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
     * @param \AppBundle\Entity\Contact|null $contact
     *
     * @return BankDetails
     */
    public function setContact(\AppBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact.
     *
     * @return \AppBundle\Entity\Contact|null
     */
    public function getContact()
    {
        return $this->contact;
    }
}
