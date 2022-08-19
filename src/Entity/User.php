<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
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
    private $firstname;

    /**
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Contact", inversedBy="user")
     */
    private $contact;

    /**
     * @ORM\Column(nullable=true)
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="user", cascade={"remove"})
     */
    private $invoice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientContract", mappedBy="user", cascade={"remove"})
     */
    private $clientContracts;

    /**
     * @ORM\Column(name="mail", type="string")
     * @Assert\Email(message="The email '{{value}}' is not valid ", checkMX=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StatementFile", mappedBy="user", cascade={"remove"})
     */
    private $statementFiles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvoiceCreationData", mappedBy="user")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvoiceRandom", mappedBy="user")
     */
    private $invoiceRandoms;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;


    public function __construct()
    {
        $this->clientContracts = new ArrayCollection();
        $this->statementFiles = new ArrayCollection();
        $this->createdAt = new ArrayCollection();
        $this->invoiceRandoms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return $this->email;
    }

    public function setUsername(string $email)
    {
        $this->email = $email;

        return $this;
    }
    

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->getUsername();
    }


    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }



    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(?string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return Collection|ClientContract[]
     */
    public function getClientContracts(): Collection
    {
        return $this->clientContracts;
    }

    public function addClientContract(ClientContract $clientContract): self
    {
        if (!$this->clientContracts->contains($clientContract)) {
            $this->clientContracts[] = $clientContract;
            $clientContract->setUser($this);
        }

        return $this;
    }

    public function removeClientContract(ClientContract $clientContract): self
    {
        if ($this->clientContracts->contains($clientContract)) {
            $this->clientContracts->removeElement($clientContract);
            // set the owning side to null (unless already changed)
            if ($clientContract->getUser() === $this) {
                $clientContract->setUser(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|StatementFile[]
     */
    public function getStatementFiles(): Collection
    {
        return $this->statementFiles;
    }

    public function addStatementFile(StatementFile $statementFile): self
    {
        if (!$this->statementFiles->contains($statementFile)) {
            $this->statementFiles[] = $statementFile;
            $statementFile->setUser($this);
        }

        return $this;
    }

    public function removeStatementFile(StatementFile $statementFile): self
    {
        if ($this->statementFiles->contains($statementFile)) {
            $this->statementFiles->removeElement($statementFile);
            // set the owning side to null (unless already changed)
            if ($statementFile->getUser() === $this) {
                $statementFile->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InvoiceCreationData[]
     */
    public function getCreatedAt(): Collection
    {
        return $this->createdAt;
    }

    public function addCreatedAt(InvoiceCreationData $createdAt): self
    {
        if (!$this->createdAt->contains($createdAt)) {
            $this->createdAt[] = $createdAt;
            $createdAt->setUser($this);
        }

        return $this;
    }

    public function removeCreatedAt(InvoiceCreationData $createdAt): self
    {
        if ($this->createdAt->contains($createdAt)) {
            $this->createdAt->removeElement($createdAt);
            // set the owning side to null (unless already changed)
            if ($createdAt->getUser() === $this) {
                $createdAt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InvoiceRandom[]
     */
    public function getInvoiceRandoms(): Collection
    {
        return $this->invoiceRandoms;
    }

    public function addInvoiceRandom(InvoiceRandom $invoiceRandom): self
    {
        if (!$this->invoiceRandoms->contains($invoiceRandom)) {
            $this->invoiceRandoms[] = $invoiceRandom;
            $invoiceRandom->setUser($this);
        }

        return $this;
    }

    public function removeInvoiceRandom(InvoiceRandom $invoiceRandom): self
    {
        if ($this->invoiceRandoms->contains($invoiceRandom)) {
            $this->invoiceRandoms->removeElement($invoiceRandom);
            // set the owning side to null (unless already changed)
            if ($invoiceRandom->getUser() === $this) {
                $invoiceRandom->setUser(null);
            }
        }

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

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }


}
