<?php

namespace Alphonse243\BioCycle\Entity;

use Carbon\Carbon;

class CycleEntity
{
    private Carbon $dateDebut;
    private Carbon $dateFin;
    private int $dureeRecue;

    public function __construct(Carbon $dateDebut, Carbon $dateFin)
    {
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->dureeRecue = $dateDebut->diffInDays($dateFin);
    }

    public function getDateDebut(): Carbon
    {
        return $this->dateDebut;
    }

    public function getDateFin(): Carbon
    {
        return $this->dateFin;
    }

    public function getDureeRecue(): int
    {
        return $this->dureeRecue;
    }
}
