<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-04
 * Time: 22:02
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ORM\Table(name="contact")
 */
class Contact
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="firstname")
     * @Assert\NotBlank()
     * @Assert\Length(min="3", minMessage="This field must be at least '{{limit}}' character long")
     *
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="2", minMessage="This field must be at least '{{limit}}' character long")
     */
    private $lastname;

    /**
     * @ORM\Column(type="date", nullable=true)
     *
     */
    private $dateofbirth;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $sexe;

    /**
     * @ORM\Column(type="string")
     */
    private $contacttype;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(min="5", minMessage="This field must be at least 5 character long")
     */
    private $socialesecunumber;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Mail", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CitizenshipDetails", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $citizenshipdetails;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientContract", mappedBy="contact")
     */
    private $clientcontract;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Phone", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BankDetails", mappedBy="contact", cascade={"persist", "remove"})
     */
    private $bankdetails;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Contract", mappedBy="contact", cascade={"persist","remove"})
     */
    private $contract;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true )
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->mail = new ArrayCollection();
        $this->address = new ArrayCollection();
        $this->phone = new ArrayCollection();
        $this->contract = new ArrayCollection();
        $this->bankdetails = new ArrayCollection();
        $this->citizenshipdetails = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }


    /**
     * @return mixed
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

    /**
     * @param mixed $datefbirth
     */
    public function setDateofbirth($dateofbirth)
    {
        $this->dateofbirth = $dateofbirth;
    }

    /**
     * @return mixed
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param mixed $sexe
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;
    }


    /**
     * @return mixed
     */
    public function getSocialesecunumber()
    {
        return $this->socialesecunumber;
    }

    /**
     * @param mixed $socialesecunumber
     */
    public function setSocialesecunumber($socialesecunumber)
    {
        $this->socialesecunumber = $socialesecunumber;
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

        // TODO: Implement trigger to update other entities linked.

    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }


    /**
     * Add mail
     *
     * @param \App\Entity\Mail $mail
     *
     * @return Contact
     */
    public function addMail(\App\Entity\Mail $mail)
    {
        if(!$this->contract->contains($mail)) {

            $mail->setContact($this);
            $this->mail[] = $mail;
        }
        return $this;
    }

    /**
     * Remove mail
     *
     * @param \App\Entity\Mail $mail
     */
    public function removeMail(\App\Entity\Mail $mail)
    {
        $this->mail->removeElement($mail);
    }

    /**
     * @return ArrayCollection|Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Add phone
     *
     * @param \App\Entity\Phone $phone
     *
     * @return Contact
     */
    public function addPhone(\App\Entity\Phone $phone)
    {
        if(!$this->contract->contains($phone)) {

            $phone->setContact($this);
            $this->phone[] = $phone;
        }
        return $this;
    }

    /**
     * Remove phone
     *
     * @param \App\Entity\Phone $phone
     */
    public function removePhone(\App\Entity\Phone $phone)
    {
        $this->phone->removeElement($phone);
    }

    /**
     * Add citizenshipdetail.
     *
     * @param \App\Entity\CitizenshipDetails $citizenshipdetail
     *
     * @return Contact
     */
    public function addCitizenshipdetail(\App\Entity\CitizenshipDetails $citizenshipdetail)
    {
        if(!$this->contract->contains($citizenshipdetail)) {
            $citizenshipdetail->setContact($this);
            $this->citizenshipdetails[] = $citizenshipdetail;
        }
        return $this;
    }

    /**
     * Remove citizenshipdetail.
     *
     * @param \App\Entity\CitizenshipDetails $citizenshipdetail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCitizenshipdetail(\App\Entity\CitizenshipDetails $citizenshipdetail)
    {
        return $this->citizenshipdetails->removeElement($citizenshipdetail);
    }

    /**
     * Get citizenshipdetails.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCitizenshipdetails()
    {
        return $this->citizenshipdetails;
    }

    /**
     * Add clientcontract.
     *
     * @param \App\Entity\ClientContract $clientcontract
     *
     * @return Contact
     */
    public function addClientcontract(\App\Entity\ClientContract $clientcontract)
    {
        if(!$this->contract->contains($clientcontract)){

            $clientcontract->setContact($this);
            $this->clientcontract[] = $clientcontract;

        }

        return $this;
    }

    /**
     * Remove clientcontract.
     *
     * @param \App\Entity\ClientContract $clientcontract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeClientcontract(\App\Entity\ClientContract $clientcontract)
    {
        return $this->clientcontract->removeElement($clientcontract);
    }

    /**
     * Get clientcontract.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientcontract()
    {
        return $this->clientcontract;
    }

    /**
     * Add address.
     *
     * @param \App\Entity\Address $address
     *
     * @return Contact
     */
    public function addAddres(\App\Entity\Address $address)
    {
        if(!$this->contract->contains($address)){

            $address->setContact($this);
            $this->address[] = $address;
        }


        return $this;
    }

    /**
     * Remove address.
     *
     * @param \App\Entity\Address $address
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAddres(\App\Entity\Address $address)
    {
        return $this->address->removeElement($address);
    }

    /**
     * Get address.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add bankdetail.
     *
     * @param \App\Entity\BankDetails $bankdetail
     *
     * @return Contact
     */
    public function addBankdetail(\App\Entity\BankDetails $bankdetail)
    {
        if(!$this->contract->contains($bankdetail)) {

            $bankdetail->setContact($this);
            $this->bankdetails[] = $bankdetail;
        }
        return $this;
    }

    /**
     * Remove bankdetail.
     *
     * @param \App\Entity\BankDetails $bankdetail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankdetail(\App\Entity\BankDetails $bankdetail)
    {
        return $this->bankdetails->removeElement($bankdetail);
    }

    /**
     * Get bankdetails.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBankdetails()
    {
        return $this->bankdetails;
    }

    /**
     * Add contract.
     *
     * @param \App\Entity\Contract $contract
     *
     * @return Contact
     */
    public function addContract(\App\Entity\Contract $contract)
    {
        if(!$this->contract->contains($contract)) {

            $contract->setContact($this);
            $this->contract[] = $contract;
        }
        return $this;
    }

    /**
     * Remove contract.
     *
     * @param \App\Entity\Contract $contract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContract(\App\Entity\Contract $contract)
    {
        return $this->contract->removeElement($contract);
    }

    /**
     * Get contract.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set contacttype.
     *
     * @param string $contacttype
     *
     * @return Contact
     */
    public function setContacttype($contacttype)
    {
        $this->contacttype = $contacttype;

        return $this;
    }

    /**
     * Get contacttype.
     *
     * @return string
     */
    public function getContacttype()
    {
        return $this->contacttype;
    }


    public function __toString()
    {

        return (string) $this->getMail();
    }
}
