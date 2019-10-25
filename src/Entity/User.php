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
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(targetEntity="App\Entity\Contact", inversedBy="user")
     */
    private $contact;

    /**
     * @ORM\Column(nullable=true)
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="user")
     */
    private $invoice;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientContract", mappedBy="user")
     */
    private $clientContracts;

    /**
     * @ORM\Column(name="mail", type="string")
     * @Assert\Email(message="The email '{{value}}' is not valid ", checkMX=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\StatementFile", mappedBy="user")
     */
    private $statementFiles;


    public function __construct()
    {
        $this->clientContracts = new ArrayCollection();
        $this->statementFiles = new ArrayCollection();
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
    public function setContact($contact): void
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


}
