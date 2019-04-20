<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * City
 *
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CityRepository")
 */
class City
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="cityname", type="string", length=255)
     */
    private $cityname;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Country", inversedBy="city")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Address", mappedBy="city")
     */
    private $address;


    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cityname.
     *
     * @param string $cityname
     *
     * @return City
     */
    public function setCityname($cityname)
    {
        $this->cityname = $cityname;

        return $this;
    }

    /**
     * Get cityname.
     *
     * @return string
     */
    public function getCityname()
    {
        return $this->cityname;
    }

    /**
     * Set country.
     *
     * @param \AppBundle\Entity\Country|null $country
     *
     * @return City
     */
    public function setCountry(\AppBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country.
     *
     * @return \AppBundle\Entity\Country|null
     */
    public function getCountry()
    {
        return $this->country;
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
     * Add address.
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return City
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
}
