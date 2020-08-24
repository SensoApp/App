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
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactEndClient", inversedBy="contract")
     */
    private $clientname;

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
     * @ORM\Column(type="float")
     */
    private $extrapercentsatyrday;

    /**
     * @ORM\Column(type="float")
     */
    private $extrapercentsunday;

    /**
     * @ORM\Column(type="float")
     */
    private $extrapercentbankholidays;


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

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clientContracts")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vat;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Timesheet", mappedBy="contract")
     */
    private $timesheets;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $active = false;




    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->invoice = new ArrayCollection();
        $this->timesheets = new ArrayCollection();
        $this->invoices = new ArrayCollection();
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

    public function getExtrapercentsatyrday(): ?float
    {
        return $this->extrapercentsatyrday;
    }

    public function setExtrapercentsatyrday(float $extrapercentsatyrday): self
    {
        $this->extrapercentsatyrday = $extrapercentsatyrday;

        return $this;
    }

    public function getExtrapercentsunday(): ?float
    {
        return $this->extrapercentsunday;
    }

    public function setExtrapercentsunday(float $extrapercentsunday): self
    {
        $this->extrapercentsunday = $extrapercentsunday;

        return $this;
    }

    public function getExtrapercentbankholidays(): ?float
    {
        return $this->extrapercentbankholidays;
    }

    public function setExtrapercentbankholidays(float $extrapercentbankholidays): self
    {
        $this->extrapercentbankholidays = $extrapercentbankholidays;

        return $this;
    }


    public function getClientname(): ?ContactEndClient
    {
        return $this->clientname;
    }

    public function setClientname(?ContactEndClient $clientname): self
    {
        $this->clientname = $clientname;

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

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(?float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return Collection|Timesheet[]
     */
    public function getTimesheets(): Collection
    {
        return $this->timesheets;
    }

    public function addTimesheet(Timesheet $timesheet): self
    {
        if (!$this->timesheets->contains($timesheet)) {
            $this->timesheets[] = $timesheet;
            $timesheet->setContract($this);
        }

        return $this;
    }

    public function removeTimesheet(Timesheet $timesheet): self
    {
        if ($this->timesheets->contains($timesheet)) {
            $this->timesheets->removeElement($timesheet);
            // set the owning side to null (unless already changed)
            if ($timesheet->getContract() === $this) {
                $timesheet->setContract(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
