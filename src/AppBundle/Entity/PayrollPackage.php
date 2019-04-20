<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PayrollPackage
 *
 * @ORM\Table(name="payroll_package")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PayrollPackageRepository")
 */
class PayrollPackage
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
     * @ORM\Column(name="packageName", type="string", length=255)
     */
    private $packageName;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Services")
     */
    private $service;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;


    public function __construct()
    {
        $this->service = new ArrayCollection();
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
     * Set packageName.
     *
     * @param string $packageName
     *
     * @return PayrollPackage
     */
    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;

        return $this;
    }

    /**
     * Get packageName.
     *
     * @return string
     */
    public function getPackageName()
    {
        return $this->packageName;
    }

    /**
     * Add service.
     *
     * @param \AppBundle\Entity\Services $service
     *
     * @return PayrollPackage
     */
    public function addService(\AppBundle\Entity\Services $service)
    {
        $this->service[] = $service;

        return $this;
    }

    /**
     * Remove service.
     *
     * @param \AppBundle\Entity\Services $service
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeService(\AppBundle\Entity\Services $service)
    {
        return $this->service->removeElement($service);
    }

    /**
     * Get service.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getService()
    {
        return $this->service;
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


}
