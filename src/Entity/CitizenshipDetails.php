<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CitizenshipDetails
 *
 * @ORM\Table(name="citizenship_details")
 * @ORM\Entity(repositoryClass="App\Repository\CitizenshipDetailsRepository")
 */
class CitizenshipDetails
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
     * @ORM\Column(name="documentType", type="string", length=255)
     */
    private $documentType;

    /**
     * @var string
     *
     * @ORM\Column(name="documentId", type="string", length=255)
     */
    private $documentId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deliveryDate", type="date")
     */
    private $deliveryDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expireDate", type="date")
     */
    private $expireDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contact", inversedBy="citizenshipdetails")
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
     * Set documentType.
     *
     * @param string $documentType
     *
     * @return CitizenshipDetails
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get documentType.
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Set documentId.
     *
     * @param string $documentId
     *
     * @return CitizenshipDetails
     */
    public function setDocumentId($documentId)
    {
        $this->documentId = $documentId;

        return $this;
    }

    /**
     * Get documentId.
     *
     * @return string
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }

    /**
     * Set deliveryDate.
     *
     * @param \DateTime $deliveryDate
     *
     * @return CitizenshipDetails
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate.
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set expireDate.
     *
     * @param \DateTime $expireDate
     *
     * @return CitizenshipDetails
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate.
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * Set contact.
     *
     * @param \App\Entity\Contact|null $contact
     *
     * @return CitizenshipDetails
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
}
