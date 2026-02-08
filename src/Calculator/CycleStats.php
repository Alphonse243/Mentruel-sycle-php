<?php

namespace Alphonse243\BioCycle\Stats;

use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Exception\CycleIrregulierException;
use Carbon\Carbon;

class CycleStats
{
   public function generateStats()
    {
        // Simulation de l'historique des 6 derniers cycles
        $cycles = [];
        for ($i = 0; $i < 6; $i++) {
            // Un cycle varie généralement entre 24 et 32 jours
            $cycles[] = rand(26, 30); 
        }

        $this->averageCycle = round(array_sum($cycles) / count($cycles), 1);
        $this->percentile = rand(75, 98);
        
        // On détermine la tendance (si le dernier cycle est plus court ou long que la moyenne)
        $lastCycle = end($cycles);
        $this->trend = $lastCycle <= $this->averageCycle ? 'Stable' : 'En variation';

        $this->chartData = collect($cycles)->map(function($height) {
            return [
                'days' => $height,
                // Calcul de la hauteur relative pour le graphique (max 35 jours pour 100%)
                'height' => ($height / 35) * 100, 
                // Couleur dynamique selon si c'est proche de la moyenne (28)
                'color' => abs($height - 28) <= 1 ? 'pink-500' : 'pink-300'
            ];
        })->toArray();
    }
    public function predict(CycleHistory $history): array
    {        $dernièresRègles = $history->getLastMenstruation()->format('d/m/Y');
        $prochainésRègles = $history->predictNextMenstruation()->format('d/m/Y');
        $ovulation = $history->predictOvulation()->format('d/m/Y');
        $fertiliteDebut = $history->predictFertilityWindowStart()->format('d/m/Y');
        $fertiliteFinDate = $history->predictFertilityWindowEnd()->format('d/m/Y');

        return [
            'dernières_règles' => $dernièresRègles,
            'prochaines_règles' => $prochainésRègles,
            'prochaines_règles_dans' => $history->predictNextMenstruation()->diffForHumans(),
            'ovulation' => $ovulation,
            'fenetre_fertilité' => sprintf(
                "du %s au %s",
                $fertiliteDebut,
                $fertiliteFinDate
            ),
            'durée_cycle_moyenne' => $this->averageCycle . ' jours',
            'ovulation_forcée' => $this->trend,
        ];
    }
}
