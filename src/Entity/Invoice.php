<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ClientContract", inversedBy="invoice")
     */
    private $contract;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invoice")
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Timesheet", inversedBy="invoice")
     */
    private $timesheet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $month;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="float")
     */
    private $totalamount;

    /**
     * @ORM\Column(type="float")
     */
    private $vat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $bankholidayamount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $saturdyamount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $sundayamount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $businessdaysamount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $invoicenumber;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentstatus;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountttc;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vatamount;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->date = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

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

    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param ClientContract $contract
     * @return Invoice
     */
    public function setContract($contract): self
    {
        $this->contract = $contract;

        return $this;
    }

    public function getTimesheet()
    {
        return $this->timesheet;
    }

    /**
     * @param Timesheet $timesheet
     * @return Invoice
     */
    public function setTimesheet($timesheet): self
    {
        $this->timesheet = $timesheet;

        return $this;
    }

    public function getTotalAmount(): ?float
    {
        return $this->totalamount;
    }

    public function setTotalAmount(float $totalamount): self
    {
        $this->totalamount = $totalamount;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param string $month
     */
    public function setMonth(string $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getBankholidayamount(): ?float
    {
        return $this->bankholidayamount;
    }

    public function setBankholidayamount(?float $bankholidayamount): self
    {
        $this->bankholidayamount = $bankholidayamount;

        return $this;
    }

    public function getSaturdyamount(): ?float
    {
        return $this->saturdyamount;
    }

    public function setSaturdyamount(?float $saturdyamount): self
    {
        $this->saturdyamount = $saturdyamount;

        return $this;
    }

    public function getSundayamount(): ?float
    {
        return $this->sundayamount;
    }

    public function setSundayamount(?float $sundayamount): self
    {
        $this->sundayamount = $sundayamount;

        return $this;
    }

    public function getBusinessdaysamount(): ?float
    {
        return $this->businessdaysamount;
    }

    public function setBusinessdaysamount(float $businessdaysamount): self
    {
        $this->businessdaysamount = $businessdaysamount;

        return $this;
    }

    public function getInvoicenumber(): ?int
    {
        return $this->invoicenumber;
    }

    public function setInvoicenumber(?int $invoicenumber): self
    {
        $this->invoicenumber = $invoicenumber;

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

    public function getPaymentstatus(): ?string
    {
        return $this->paymentstatus;
    }

    public function setPaymentstatus(?string $paymentstatus): self
    {
        $this->paymentstatus = $paymentstatus;

        return $this;
    }

    public function getAmountttc(): ?float
    {
        return $this->amountttc;
    }

    public function setAmountttc(?float $amountttc): self
    {
        $this->amountttc = $amountttc;

        return $this;
    }

    public function getVatamount(): ?float
    {
        return $this->vatamount;
    }

    public function setVatamount(?float $vatamount): self
    {
        $this->vatamount = $vatamount;

        return $this;
    }



}
