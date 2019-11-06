<?php

namespace App\Entity;

class SimulationFees
{

    const CAISSE_MALADIE = 0.28;
    const CAISSE_MALADIE_ESPECE = 0.0025;
    const CAISSE_PENSION = 0.08;
    const ASSURANCE_DEPENDANCE = 0.14;
    const SOINS_SANTE = 0.0011;
    const CMU = 0.107;

    private $caissemaladie;

    private $caissemaladieespece;

    private $caissepension;

    private $assurancedependance;

    private $soinsante;

    private $cmu;


    public function getCaissemaladie(): ?float
    {
        return $this->caissemaladie;
    }

    public function setCaissemaladie(): self
    {
        $this->caissemaladie = self::CAISSE_MALADIE;

        return $this;
    }

    public function getCaissemaladieespece(): ?float
    {
        return $this->caissemaladieespece;
    }

    public function setCaissemaladieespece(): self
    {
        $this->caissemaladieespece = self::CAISSE_MALADIE_ESPECE;

        return $this;
    }

    public function getCaissepension(): ?float
    {
        return $this->caissepension;
    }

    public function setCaissepension(): self
    {
        $this->caissepension = self::CAISSE_PENSION;

        return $this;
    }

    public function getAssurancedependance(): ?float
    {
        return $this->assurancedependance;
    }

    public function setAssurancedependance(): self
    {
        $this->assurancedependance = self::ASSURANCE_DEPENDANCE;

        return $this;
    }

    public function getSoinsante(): ?float
    {
        return $this->soinsante;
    }

    public function setSoinsante(): self
    {
        $this->soinsante = self::SOINS_SANTE;

        return $this;
    }

    public function getCmu(): ?float
    {
        return $this->cmu;
    }

    public function setCmu(): self
    {
        $this->cmu = self::CMU;

        return $this;
    }
}
