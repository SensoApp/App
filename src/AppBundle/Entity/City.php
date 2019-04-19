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
}
