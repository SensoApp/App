<?php
/**
 * Created by PhpStorm.
 * User: MacBookAir
 * Date: 2019-04-04
 * Time: 22:02
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
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
     */
    private $firstname;

    /**
     * @ORM\Column(type="string")
     */
    private $lastname;

    /**
     * @ORM\Column(type="date")
     */
    private $datefbirth;

    /**
     * @ORM\Column(type="string")
     */
    private $sexe;

    /**
     * @ORM\Column(type="string")
     */
    private $contacttype;

    /**
     * @ORM\Column(type="string")
     */
    private $socialesecunumber;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Mail", mappedBy="contact")
     */
    private $mail;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CitizenshipDetails", mappedBy="contact")
     */
    private $citizenshipdetails;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ClientContract", mappedBy="contact")
     */
    private $clientcontract;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Phone", mappedBy="contact")
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Address", mappedBy="contact")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BankDetails", mappedBy="contact")
     */
    private $bankdetails;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contract", mappedBy="contact")
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
    public function getDatefbirth()
    {
        return $this->datefbirth;
    }

    /**
     * @param mixed $datefbirth
     */
    public function setDatefbirth($datefbirth)
    {
        $this->datefbirth = $datefbirth;
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
     * @param \AppBundle\Entity\Mail $mail
     *
     * @return Contact
     */
    public function addMail(\AppBundle\Entity\Mail $mail)
    {
        $this->mail[] = $mail;

        return $this;
    }

    /**
     * Remove mail
     *
     * @param \AppBundle\Entity\Mail $mail
     */
    public function removeMail(\AppBundle\Entity\Mail $mail)
    {
        $this->mail->removeElement($mail);
    }

    /**
     * @return mixed
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
     * @param \AppBundle\Entity\Phone $phone
     *
     * @return Contact
     */
    public function addPhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phone[] = $phone;

        return $this;
    }

    /**
     * Remove phone
     *
     * @param \AppBundle\Entity\Phone $phone
     */
    public function removePhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phone->removeElement($phone);
    }

    /**
     * Add citizenshipdetail.
     *
     * @param \AppBundle\Entity\CitizenshipDetails $citizenshipdetail
     *
     * @return Contact
     */
    public function addCitizenshipdetail(\AppBundle\Entity\CitizenshipDetails $citizenshipdetail)
    {
        $this->citizenshipdetails[] = $citizenshipdetail;

        return $this;
    }

    /**
     * Remove citizenshipdetail.
     *
     * @param \AppBundle\Entity\CitizenshipDetails $citizenshipdetail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCitizenshipdetail(\AppBundle\Entity\CitizenshipDetails $citizenshipdetail)
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
     * @param \AppBundle\Entity\ClientContract $clientcontract
     *
     * @return Contact
     */
    public function addClientcontract(\AppBundle\Entity\ClientContract $clientcontract)
    {
        $this->clientcontract[] = $clientcontract;

        return $this;
    }

    /**
     * Remove clientcontract.
     *
     * @param \AppBundle\Entity\ClientContract $clientcontract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeClientcontract(\AppBundle\Entity\ClientContract $clientcontract)
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
     * @param \AppBundle\Entity\Address $address
     *
     * @return Contact
     */
    public function addAddress(\AppBundle\Entity\Address $address)
    {
        $this->address[] = $address;

        return $this;
    }

    /**
     * Remove address.
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAddress(\AppBundle\Entity\Address $address)
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
     * @param \AppBundle\Entity\BankDetails $bankdetail
     *
     * @return Contact
     */
    public function addBankdetail(\AppBundle\Entity\BankDetails $bankdetail)
    {
        $this->bankdetails[] = $bankdetail;

        return $this;
    }

    /**
     * Remove bankdetail.
     *
     * @param \AppBundle\Entity\BankDetails $bankdetail
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBankdetail(\AppBundle\Entity\BankDetails $bankdetail)
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
     * @param \AppBundle\Entity\ClientContract $contract
     *
     * @return Contact
     */
    public function addContract(\AppBundle\Entity\ClientContract $contract)
    {
        $this->contract[] = $contract;

        return $this;
    }

    /**
     * Remove contract.
     *
     * @param \AppBundle\Entity\ClientContract $contract
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeContract(\AppBundle\Entity\ClientContract $contract)
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
}
