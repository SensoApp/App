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
     * @ORM\Column(type="integer")
     */
    private $nbreDaysWorked;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbreDaysOff;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreOfSaturdays;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreOfSundays;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->date = new \DateTime('now');
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

    public function getNbreDaysOff(): ?int
    {
        return $this->nbreDaysOff;
    }

    public function setNbreDaysOff(int $nbreDaysOff): self
    {
        $this->nbreDaysOff = $nbreDaysOff;

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

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): self
    {
        $this->user = $user;

        return $this;
    }
}
