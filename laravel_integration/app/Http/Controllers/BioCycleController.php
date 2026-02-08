<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Alphonse243\BioCycle\Calculator\CycleCalculator;
use Alphonse243\BioCycle\Exception\CycleIrregulierException;
use Carbon\Carbon;

class BioCycleController extends Controller
{
	// Affiche la démo / calcule la prédiction.
	// Accepté en GET (échantillon) ou POST (données utilisateur : 'cycles' => [['start'=>'Y-m-d','end'=>'Y-m-d'], ...], 'last_period' => 'Y-m-d')
	public function demo(Request $request)
	{
		$inputCycles = $request->input('cycles', [
			['start' => '2024-08-15', 'end' => '2024-09-12'],
			['start' => '2024-09-12', 'end' => '2024-10-10'],
			['start' => '2024-10-10', 'end' => '2024-11-07'],
			['start' => '2024-11-07', 'end' => '2024-12-05'],
		]);

		$history = new CycleHistory();
		foreach ($inputCycles as $c) {
			try {
				$start = Carbon::createFromFormat('Y-m-d', $c['start']);
				$end = Carbon::createFromFormat('Y-m-d', $c['end']);
				$history->addCycle(new CycleEntity($start, $end));
			} catch (\Exception $e) {
				// skip invalid entry
			}
		}

		$lastPeriodStr = $request->input('last_period', $inputCycles[count($inputCycles)-1]['start'] ?? Carbon::now()->format('Y-m-d'));
		try {
			$lastPeriod = Carbon::createFromFormat('Y-m-d', $lastPeriodStr);
		} catch (\Exception $e) {
			$lastPeriod = Carbon::now();
		}

		$calculator = new CycleCalculator($history, $lastPeriod);

		try {
			$formatted = $calculator->getFormattedPrediction('fr');
			$error = null;
		} catch (CycleIrregulierException $e) {
			$formatted = [];
			$error = $e->getMessage();
		} catch (\Exception $e) {
			$formatted = [];
			$error = 'Erreur interne : ' . $e->getMessage();
		}

		// Renvoyer la vue (placez la vue dans resources/views/laravel_integration/cycle.blade.php ou adaptez)
		return view('laravel_integration.cycle', [
			'prediction' => $formatted,
			'count' => $history->count(),
			'error' => $error,
		]);
	}
}
