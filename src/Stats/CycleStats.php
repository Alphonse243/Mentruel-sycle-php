<?php

namespace Alphonse243\BioCycle\Stats;

use Alphonse243\BioCycle\Collection\CycleHistory;

class CycleStats
{
    private CycleHistory $history;
    private float $averageCycle = 0;
    private int $percentile = 0;
    private string $trend = 'Stable';
    private array $chartData = [];

    public function __construct(CycleHistory $history)
    {
        $this->history = $history;
    }

    /**
     * Génère les statistiques basées sur l'historique
     */
    public function generateStats(): array
    {
        if ($this->history->count() === 0) {
            return [
                'average_cycle' => 28,
                'percentile' => 0,
                'trend' => 'Pas de données',
                'cycle_count' => 0,
                'chart_data' => [],
            ];
        }

        // Extraire les durées de cycles
        $cycles = array_map(
            fn($c) => $c->getDureeRecue(),
            $this->history->getCycles()
        );

        // Calculer la moyenne
        $this->averageCycle = round(array_sum($cycles) / count($cycles), 1);

        // Calculer le percentile (hauteur relative sur max 35 jours)
        $this->percentile = (int) round(($this->averageCycle / 35) * 100);

        // Déterminer la tendance
        $lastCycle = end($cycles);
        $this->trend = $lastCycle <= $this->averageCycle ? 'Stable' : 'En variation';

        // Générer les données pour le graphique
        $this->chartData = array_map(function ($days) {
            return [
                'days' => $days,
                'height' => ($days / 35) * 100,
                'color' => abs($days - 28) <= 1 ? 'pink-500' : 'pink-300',
            ];
        }, $cycles);

        return $this->toArray();
    }

    /**
     * Retourne les statistiques sous forme de tableau
     */
    public function toArray(): array
    {
        return [
            'average_cycle' => $this->averageCycle,
            'percentile' => $this->percentile,
            'trend' => $this->trend,
            'cycle_count' => $this->history->count(),
            'chart_data' => $this->chartData,
        ];
    }

    /**
     * Retourne la moyenne
     */
    public function getAverageCycle(): float
    {
        return $this->averageCycle;
    }

    /**
     * Retourne le percentile
     */
    public function getPercentile(): int
    {
        return $this->percentile;
    }

    /**
     * Retourne la tendance
     */
    public function getTrend(): string
    {
        return $this->trend;
    }

    /**
     * Retourne les données du graphique
     */
    public function getChartData(): array
    {
        return $this->chartData;
    }
}
