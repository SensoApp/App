<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactEndClientRepository")
 */
class ContactEndClient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contactperson;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="clientcontact")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phone", mappedBy="clientcontact")
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mail", mappedBy="clientcontact")
     */
    private $mail;

    public function __construct()
    {
        $this->address = new ArrayCollection();
        $this->phone = new ArrayCollection();
        $this->mail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientname(): ?string
    {
        return $this->clientname;
    }

    public function setClientname(string $clientname): self
    {
        $this->clientname = $clientname;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getContactperson(): ?string
    {
        return $this->contactperson;
    }

    public function setContactperson(string $contactperson): self
    {
        $this->contactperson = $contactperson;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddress(): Collection
    {
        return $this->address;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->address->contains($address)) {
            $this->address[] = $address;
            $address->setClientcontact($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->address->contains($address)) {
            $this->address->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getClientcontact() === $this) {
                $address->setClientcontact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Phone[]
     */
    public function getPhone(): Collection
    {
        return $this->phone;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phone->contains($phone)) {
            $this->phone[] = $phone;
            $phone->setClientcontact($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phone->contains($phone)) {
            $this->phone->removeElement($phone);
            // set the owning side to null (unless already changed)
            if ($phone->getClientcontact() === $this) {
                $phone->setClientcontact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mail[]
     */
    public function getMail(): Collection
    {
        return $this->mail;
    }

    public function addMail(Mail $mail): self
    {
        if (!$this->mail->contains($mail)) {
            $this->mail[] = $mail;
            $mail->setClientcontact($this);
        }

        return $this;
    }

    public function removeMail(Mail $mail): self
    {
        if ($this->mail->contains($mail)) {
            $this->mail->removeElement($mail);
            // set the owning side to null (unless already changed)
            if ($mail->getClientcontact() === $this) {
                $mail->setClientcontact(null);
            }
        }

        return $this;
    }
}
