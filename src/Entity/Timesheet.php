<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TimesheetRepository")
 */
class Timesheet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $month;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreDaysWorked;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrOfBankHolidays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreOfSaturdays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreOfSundays;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Invoice", mappedBy="timesheet")
     */
    private $invoice;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientContract", inversedBy="timesheets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contract;


    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbreDaysWorked(): ?int
    {
        return $this->nbreDaysWorked;
    }

    public function setNbreDaysWorked(int $nbreDaysWorked): self
    {
        $this->nbreDaysWorked = $nbreDaysWorked;

        return $this;
    }


    public function getNbreOfSaturdays(): ?int
    {
        return $this->nbreOfSaturdays;
    }

    public function setNbreOfSaturdays(int $nbreOfSaturdays): self
    {
        $this->nbreOfSaturdays = $nbreOfSaturdays;

        return $this;
    }

    public function getNbreOfSundays(): ?int
    {
        return $this->nbreOfSundays;
    }

    public function setNbreOfSundays(int $nbreOfSundays): self
    {
        $this->nbreOfSundays = $nbreOfSundays;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNbrOfBankHolidays(): ?int
    {
        return $this->nbrOfBankHolidays;
    }

    public function setNbrOfBankHolidays(?int $nbrOfBankHolidays): self
    {
        $this->nbrOfBankHolidays = $nbrOfBankHolidays;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;

        // set (or unset) the owning side of the relation if necessary
        $newTimesheet = $invoice === null ? null : $this;
        if ($newTimesheet !== $invoice->getTimesheet()) {
            $invoice->setTimesheet($newTimesheet);
        }

        return $this;
    }

    public function getContract(): ?ClientContract
    {
        return $this->contract;
    }

    public function setContract(?ClientContract $contract): self
    {
        $this->contract = $contract;

        return $this;
    }
}
