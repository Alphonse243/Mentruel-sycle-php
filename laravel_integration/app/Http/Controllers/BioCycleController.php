<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Alphonse243\BioCycle\Calculator\CycleCalculator;
use Carbon\Carbon;

class BioCycleController extends Controller
{
    public function demo()
    {
        // Exemple d'historique (remplacez par vos données utilisateur)
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::createFromFormat('Y-m-d', '2024-08-15'), Carbon::createFromFormat('Y-m-d', '2024-09-12')));
        $history->addCycle(new CycleEntity(Carbon::createFromFormat('Y-m-d', '2024-09-12'), Carbon::createFromFormat('Y-m-d', '2024-10-10')));
        $history->addCycle(new CycleEntity(Carbon::createFromFormat('Y-m-d', '2024-10-10'), Carbon::createFromFormat('Y-m-d', '2024-11-07')));
        $lastPeriod = Carbon::createFromFormat('Y-m-d', '2024-11-07');

        $calculator = new CycleCalculator($history, $lastPeriod);
        $formatted = $calculator->getFormattedPrediction('fr');

        return view('laravel_integration.cycle', ['prediction' => $formatted, 'count' => $history->count()]);
    }
}
