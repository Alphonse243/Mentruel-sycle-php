<?php

namespace Alphonse243\BioCycle\Calculator;

use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Exception\CycleIrregulierException;
use Carbon\Carbon;

class CycleCalculator
{
    private CycleHistory $history;
    private Carbon $lastPeriodDate;
    private ?Carbon $forcedOvulationDate = null;

    public function __construct(CycleHistory $history, Carbon $lastPeriodDate)
    {
        $this->history = $history;
        $this->lastPeriodDate = $lastPeriodDate->copy();
    }

    /**
     * RÈGLE C : Permet de forcer la date d'ovulation si détection physique
     */
    public function forceOvulationDate(Carbon $date): self
    {
        $this->forcedOvulationDate = $date->copy();
        return $this;
    }

    /**
     * Calcule la prédiction du prochain cycle
     */
    public function predictNextCycle(): array
    {
        $average = $this->history->getAverageDuration();

        // RÈGLE B : Détection d'anomalie
        if ($this->history->count() > 0) {
            $lastCycleDuration = $this->history->getCycles()[count($this->history->getCycles()) - 1]->getDureeRecue();
            if (abs($lastCycleDuration - $average) > 7) {
                throw new CycleIrregulierException(
                    "Cycle irrégulier détecté : dernier cycle = {$lastCycleDuration}j, moyenne = {$average}j"
                );
            }
        }

        $nextPeriod = $this->lastPeriodDate->copy()->addDays($average);
        
        // RÈGLE C : Ovulation forcée écrase le calcul
        if ($this->forcedOvulationDate) {
            $ovulation = $this->forcedOvulationDate->copy();
        } else {
            $ovulation = $nextPeriod->copy()->subDays(14);
        }

        $fertilityStart = $ovulation->copy()->subDays(4);
        $fertilityEnd = $ovulation->copy()->addDays(1);

        return [
            'dernières_règles' => $this->lastPeriodDate,
            'prochaines_règles' => $nextPeriod,
            'ovulation' => $ovulation,
            'fenetre_fertilité_début' => $fertilityStart,
            'fenetre_fertilité_fin' => $fertilityEnd,
            'durée_cycle_moyenne' => $average,
            'ovulation_forcée' => $this->forcedOvulationDate !== null,
        ];
    }

    /**
     * Formatte les résultats pour affichage utilisateur
     */
    public function getFormattedPrediction(string $locale = 'fr'): array
    {
        $prediction = $this->predictNextCycle();

        return [
            'dernières_règles' => $prediction['dernières_règles']->translatedFormat('d F Y', locale: $locale),
            'prochaines_règles' => $prediction['prochaines_règles']->translatedFormat('d F Y', locale: $locale),
            'prochaines_règles_dans' => $prediction['prochaines_règles']->diffForHumans(),
            'ovulation' => $prediction['ovulation']->translatedFormat('d F Y', locale: $locale),
            'fenetre_fertilité' => sprintf(
                "du %s au %s",
                $prediction['fenetre_fertilité_début']->translatedFormat('d F', locale: $locale),
                $prediction['fenetre_fertilité_fin']->translatedFormat('d F Y', locale: $locale)
            ),
            'durée_cycle_moyenne' => $prediction['durée_cycle_moyenne'] . ' jours',
            'ovulation_forcée' => $prediction['ovulation_forcée'],
        ];
    }
}
