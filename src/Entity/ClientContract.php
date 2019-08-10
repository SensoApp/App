<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ClientContract
 *
 * @ORM\Table(name="client_contract")
 * @ORM\Entity(repositoryClass="App\Repository\ClientContractRepository")
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
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="date", nullable=true)
     */
    private $endDate;

    /**
     * @var int
     *
     * @ORM\Column(name="rate", type="integer")
     */
    private $rate;

    /**
     * @ORM\Column(type="integer")
     */
    private $extrapercentsatyrday;

    /**
     * @ORM\Column(type="integer")
     */
    private $extrapercentsunday;

    /**
     * @ORM\Column(type="integer")
     */
    private $extrapercentbankholidays;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clientcontract")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="contract")
     */
    private $invoice;
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
        $this->invoice = new ArrayCollection();
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

    public function getExtrapercentsatyrday(): ?int
    {
        return $this->extrapercentsatyrday;
    }

    public function setExtrapercentsatyrday(int $extrapercentsatyrday): self
    {
        $this->extrapercentsatyrday = $extrapercentsatyrday;

        return $this;
    }

    public function getExtrapercentsunday(): ?int
    {
        return $this->extrapercentsunday;
    }

    public function setExtrapercentsunday(int $extrapercentsunday): self
    {
        $this->extrapercentsunday = $extrapercentsunday;

        return $this;
    }

    public function getExtrapercentbankholidays(): ?int
    {
        return $this->extrapercentbankholidays;
    }

    public function setExtrapercentbankholidays(int $extrapercentbankholidays): self
    {
        $this->extrapercentbankholidays = $extrapercentbankholidays;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoice(): Collection
    {
        return $this->invoice;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoice->contains($invoice)) {
            $this->invoice[] = $invoice;
            $invoice->setContract($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoice->contains($invoice)) {
            $this->invoice->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getContract() === $this) {
                $invoice->setContract(null);
            }
        }

        return $this;
    }
}
