<?php


namespace App\Entity;


class SimulationCalculationDetails
{
    private $grosssalary;

    private $rate;

    private $numberofdaysworked;

    private $remainder;

    private $taxableincome;

    private $taxamount;

    /**
     * @return mixed
     */
    public function getGrosssalary()
    {
        return $this->grosssalary;
    }

    /**
     * @param mixed $grosssalary
     */
    public function setGrosssalary($grosssalary): void
    {
        $this->grosssalary = $grosssalary;
    }

    /**
     * @return mixed
     */
    public function getRemainder()
    {
        return $this->remainder;
    }

    /**
     * @param mixed $remainder
     */
    public function setRemainder($remainder): void
    {
        $this->remainder = $remainder;
    }

    /**
     * @return mixed
     */
    public function getTaxableincome()
    {
        return $this->taxableincome;
    }

    /**
     * @param mixed $taxableincome
     */
    public function setTaxableincome($taxableincome): void
    {
        $this->taxableincome = $taxableincome;
    }

    /**
     * @return mixed
     */
    public function getTaxamount()
    {
        return $this->taxamount;
    }

    /**
     * @param mixed $taxamount
     */
    public function setTaxamount($taxamount): void
    {
        $this->taxamount = $taxamount;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate): void
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getNumberofdaysworked()
    {
        return $this->numberofdaysworked;
    }

    /**
     * @param mixed $numberofdaysworked
     */
    public function setNumberofdaysworked($numberofdaysworked): void
    {
        $this->numberofdaysworked = $numberofdaysworked;
    }

}