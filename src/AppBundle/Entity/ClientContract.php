<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientContract
 *
 * @ORM\Table(name="client_contract")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientContractRepository")
 */
class ClientContract
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
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="date")
     */
    private $startDate;

    /**
     * @var string
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var string
     *
     * @ORM\Column(name="contracttype", type="string", length=255)
     */
    private $contracttype;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="clientcontract")
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
     * Set startDate.
     *
     * @param \DateTime $startDate
     *
     * @return ClientContract
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
     * @param string $endDate
     *
     * @return ClientContract
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate.
     *
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set contracttype.
     *
     * @param string $contracttype
     *
     * @return ClientContract
     */
    public function setContracttype($contracttype)
    {
        $this->contracttype = $contracttype;

        return $this;
    }

    /**
     * Get contracttype.
     *
     * @return string
     */
    public function getContracttype()
    {
        return $this->contracttype;
    }

    /**
     * Set rate.
     *
     * @param int $rate
     *
     * @return ClientContract
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate.
     *
     * @return int
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set contact.
     *
     * @param \AppBundle\Entity\Contact|null $contact
     *
     * @return ClientContract
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
