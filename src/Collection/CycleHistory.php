<?php

namespace Alphonse243\BioCycle\Collection;

use Alphonse243\BioCycle\Entity\CycleEntity;

class CycleHistory
{
    /** @var CycleEntity[] */
    private array $cycles = [];

    public function addCycle(CycleEntity $cycle): self
    {
        $this->cycles[] = $cycle;
        return $this;
    }

    public function getCycles(): array
    {
        return $this->cycles;
    }

    public function getAverageDuration(int $lastNcycles = 6): float
    {
        // RÈGLE A : Moyenne mobile sur les 6 derniers cycles
        $cyclesToUse = array_slice($this->cycles, -$lastNcycles);
        
        if (empty($cyclesToUse)) {
            return 28; // Valeur par défaut si pas d'historique
        }

        $totalDays = array_sum(array_map(fn($c) => $c->getDureeRecue(), $cyclesToUse));
        return round($totalDays / count($cyclesToUse), 1);
    }

    public function count(): int
    {
        return count($this->cycles);
    }
}
