<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceCreationDataRepository")
 */
class InvoiceCreationData
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
     * @ORM\Column(type="float")
     */
    private $daysWorked;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $bankHolidays;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $workSaturdays;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $workSundays;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="createdAt")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     */
    public function setMonth($month): void
    {
        $this->month = $month;
    }




    public function getDaysWorked(): ?float
    {
        return $this->daysWorked;
    }

    public function setDaysWorked(float $daysWorked): self
    {
        $this->daysWorked = $daysWorked;

        return $this;
    }

    public function getBankHolidays(): ?float
    {
        return $this->bankHolidays;
    }

    public function setBankHolidays(?float $bankHolidays): self
    {
        $this->bankHolidays = $bankHolidays;

        return $this;
    }

    public function getWorkSaturdays(): ?float
    {
        return $this->workSaturdays;
    }

    public function setWorkSaturdays(?float $workSaturdays): self
    {
        $this->workSaturdays = $workSaturdays;

        return $this;
    }

    public function getWorkSundays(): ?float

    {
        return $this->workSundays;
    }

    public function setWorkSundays(?float $workSundays): self
    {
        $this->workSundays = $workSundays;

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
}
