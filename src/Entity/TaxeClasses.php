<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxeClassesRepository")
 */
class TaxeClasses
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $tranche;

    /**
     * @ORM\Column(type="integer")
     */
    private $fromsalarytranche;

    /**
     * @ORM\Column(type="float")
     */
    private $class1;

    /**
     * @ORM\Column(type="float")
     */
    private $class1a;

    /**
     * @ORM\Column(type="float")
     */
    private $class2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTranche(): ?int
    {
        return $this->tranche;
    }

    public function setTranche(int $tranche): self
    {
        $this->tranche = $tranche;

        return $this;
    }

    public function getFromsalarytranche(): ?int
    {
        return $this->fromsalarytranche;
    }

    public function setFromsalarytranche(int $fromsalarytranche): self
    {
        $this->fromsalarytranche = $fromsalarytranche;

        return $this;
    }

    public function getClass1(): ?float
    {
        return $this->class1;
    }

    public function setClass1(float $class1): self
    {
        $this->class1 = $class1;

        return $this;
    }

    public function getClass1a(): ?float
    {
        return $this->class1a;
    }

    public function setClass1a(float $class1a): self
    {
        $this->class1a = $class1a;

        return $this;
    }

    public function getClass2(): ?float
    {
        return $this->class2;
    }

    public function setClass2(float $class2): self
    {
        $this->class2 = $class2;

        return $this;
    }
}
