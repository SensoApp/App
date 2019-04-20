<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContractRepository")
 */
class Contract
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
     * @ORM\Column(name="contractType", type="string", length=255)
     */
    private $contractType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="probationPeriodEndDate", type="date", nullable=true)
     */
    private $probationPeriodEndDate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="contract")
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
     * Set contractType.
     *
     * @param string $contractType
     *
     * @return Contract
     */
    public function setContractType($contractType)
    {
        $this->contractType = $contractType;

        return $this;
    }

    /**
     * Get contractType.
     *
     * @return string
     */
    public function getContractType()
    {
        return $this->contractType;
    }

    /**
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return Contract
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate.
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate.
     *
     * @param \DateTime|null $endDate
     *
     * @return Contract
     */
    public function setEndDate($endDate = null)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return \DateTime|null
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set probationPeriodEndDate.
     *
     * @param \DateTime|null $probationPeriodEndDate
     *
     * @return Contract
     */
    public function setProbationPeriodEndDate($probationPeriodEndDate = null)
    {
        $this->probationPeriodEndDate = $probationPeriodEndDate;

        return $this;
    }

    /**
     * Get probationPeriodEndDate.
     *
     * @return \DateTime|null
     */
    public function getProbationPeriodEndDate()
    {
        return $this->probationPeriodEndDate;
    }

    /**
     * Set contact.
     *
     * @param \AppBundle\Entity\Contact|null $contact
     *
     * @return Contract
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
