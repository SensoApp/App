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
     * @ORM\Column(type="integer")
     */
    private $daysWorked;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bankHolidays;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $workSaturdays;

    /**
     * @ORM\Column(type="integer", nullable=true)
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




    public function getDaysWorked(): ?int
    {
        return $this->daysWorked;
    }

    public function setDaysWorked(int $daysWorked): self
    {
        $this->daysWorked = $daysWorked;

        return $this;
    }

    public function getBankHolidays(): ?int
    {
        return $this->bankHolidays;
    }

    public function setBankHolidays(?int $bankHolidays): self
    {
        $this->bankHolidays = $bankHolidays;

        return $this;
    }

    public function getWorkSaturdays(): ?int
    {
        return $this->workSaturdays;
    }

    public function setWorkSaturdays(?int $workSaturdays): self
    {
        $this->workSaturdays = $workSaturdays;

        return $this;
    }

    public function getWorkSundays(): ?int
    {
        return $this->workSundays;
    }

    public function setWorkSundays(?int $workSundays): self
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
