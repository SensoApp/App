<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Services
 *
 * @ORM\Table(name="services")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ServicesRepository")
 */
class Services
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
     * @ORM\Column(name="servicename", type="string", length=255)
     */
    private $servicename;


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
     * Set servicename.
     *
     * @param string $servicename
     *
     * @return Services
     */
    public function setServicename($servicename)
    {
        $this->servicename = $servicename;

        return $this;
    }

    /**
     * Get servicename.
     *
     * @return string
     */
    public function getServicename()
    {
        return $this->servicename;
    }
}
