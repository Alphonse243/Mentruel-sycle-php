<?php

require_once __DIR__ . '/vendor/autoload.php';

use Alphonse243\BioCycle\Calculator\CycleCalculator;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Alphonse243\BioCycle\Exception\CycleIrregulierException;
use Carbon\Carbon;

echo "========================================\n";
echo "🔬 TESTS DU SYSTÈME BIOCYCLE-PREDICTOR\n";
echo "========================================\n\n";

// TEST 1 : Cycle parfait de 28 jours
echo "TEST 1️⃣ : Cycle parfait de 28 jours\n";
echo "-----------------------------------\n";
try {
    $history1 = new CycleHistory();
    $history1->addCycle(new CycleEntity(
        Carbon::parse('2024-01-01'),
        Carbon::parse('2024-01-29')
    ));
    $history1->addCycle(new CycleEntity(
        Carbon::parse('2024-01-29'),
        Carbon::parse('2024-02-26')
    ));

    $calc1 = new CycleCalculator($history1, Carbon::parse('2024-02-26'));
    $pred1 = $calc1->predictNextCycle();

    echo "✅ Durée moyenne : " . $pred1['durée_cycle_moyenne'] . " jours\n";
    echo "✅ Prochaines règles : " . $pred1['prochaines_règles']->format('Y-m-d') . "\n";
    echo "✅ Ovulation : " . $pred1['ovulation']->format('Y-m-d') . "\n";
    echo "✅ TEST 1 RÉUSSI\n\n";
} catch (Exception $e) {
    echo "❌ TEST 1 ÉCHOUÉ : " . $e->getMessage() . "\n\n";
}

// TEST 2 : Recalcul de moyenne après cycle irrégulier
echo "TEST 2️⃣ : Recalcul de moyenne (35 et 28 jours)\n";
echo "---------------------------------------------\n";
try {
    $history2 = new CycleHistory();
    $history2->addCycle(new CycleEntity(
        Carbon::parse('2024-01-01'),
        Carbon::parse('2024-01-29')
    )); // 28 jours
    $history2->addCycle(new CycleEntity(
        Carbon::parse('2024-01-29'),
        Carbon::parse('2024-03-05')
    )); // 35 jours

    $average = $history2->getAverageDuration();
    echo "✅ Cycle 1 : 28 jours\n";
    echo "✅ Cycle 2 : 35 jours\n";
    echo "✅ Moyenne mobile : " . $average . " jours\n";
    echo "✅ TEST 2 RÉUSSI\n\n";
} catch (Exception $e) {
    echo "❌ TEST 2 ÉCHOUÉ : " . $e->getMessage() . "\n\n";
}

// TEST 3 : Passage d'années
echo "TEST 3️⃣ : Gestion des passages d'années (déc->jan)\n";
echo "---------------------------------------------------\n";
try {
    $history3 = new CycleHistory();
    $history3->addCycle(new CycleEntity(
        Carbon::parse('2023-12-01'),
        Carbon::parse('2023-12-29')
    ));
    $history3->addCycle(new CycleEntity(
        Carbon::parse('2023-12-29'),
        Carbon::parse('2024-01-26')
    ));

    $calc3 = new CycleCalculator($history3, Carbon::parse('2024-01-26'));
    $pred3 = $calc3->predictNextCycle();

    echo "✅ Cycle débute : 2023-12-01\n";
    echo "✅ Cycle se termine : 2024-01-26\n";
    echo "✅ Prochaines règles : " . $pred3['prochaines_règles']->format('Y-m-d') . "\n";
    echo "✅ Année correcte : " . $pred3['prochaines_règles']->year . "\n";
    echo "✅ TEST 3 RÉUSSI\n\n";
} catch (Exception $e) {
    echo "❌ TEST 3 ÉCHOUÉ : " . $e->getMessage() . "\n\n";
}

// TEST 4 : Exception cycle irrégulier (écart > 7 jours)
echo "TEST 4️⃣ : Détection d'anomalie (écart > 7 jours)\n";
echo "-----------------------------------------------\n";
try {
    $history4 = new CycleHistory();
    $history4->addCycle(new CycleEntity(
        Carbon::parse('2024-01-01'),
        Carbon::parse('2024-01-29')
    )); // 28 jours
    $history4->addCycle(new CycleEntity(
        Carbon::parse('2024-01-29'),
        Carbon::parse('2024-03-10')
    )); // 40 jours (écart de 12 > 7)

    $calc4 = new CycleCalculator($history4, Carbon::parse('2024-03-10'));
    $calc4->predictNextCycle();

    echo "❌ TEST 4 ÉCHOUÉ : Exception non levée\n\n";
} catch (CycleIrregulierException $e) {
    echo "✅ Exception levée correctement\n";
    echo "✅ Message : " . $e->getMessage() . "\n";
    echo "✅ TEST 4 RÉUSSI\n\n";
}

// TEST 5 : Ovulation forcée
echo "TEST 5️⃣ : Ovulation forcée (détection physique)\n";
echo "-----------------------------------------------\n";
try {
    $history5 = new CycleHistory();
    $history5->addCycle(new CycleEntity(
        Carbon::parse('2024-01-01'),
        Carbon::parse('2024-01-29')
    ));

    $calc5 = new CycleCalculator($history5, Carbon::parse('2024-01-29'));
    $forcedDate = Carbon::parse('2024-02-15');
    $calc5->forceOvulationDate($forcedDate);

    $pred5 = $calc5->predictNextCycle();
    echo "✅ Date d'ovulation forcée : " . $forcedDate->format('Y-m-d') . "\n";
    echo "✅ Ovulation calculée : " . $pred5['ovulation']->format('Y-m-d') . "\n";
    echo "✅ Ovulation forcée : " . ($pred5['ovulation_forcée'] ? 'OUI' : 'NON') . "\n";
    echo "✅ TEST 5 RÉUSSI\n\n";
} catch (Exception $e) {
    echo "❌ TEST 5 ÉCHOUÉ : " . $e->getMessage() . "\n\n";
}

// TEST 6 : Formatage pour affichage utilisateur
echo "TEST 6️⃣ : Formatage pour l'utilisateur (translatedFormat)\n";
echo "------------------------------------------------------\n";
try {
    $history6 = new CycleHistory();
    $history6->addCycle(new CycleEntity(
        Carbon::parse('2024-10-01'),
        Carbon::parse('2024-10-29')
    ));
    $history6->addCycle(new CycleEntity(
        Carbon::parse('2024-10-29'),
        Carbon::parse('2024-11-26')
    ));

    $calc6 = new CycleCalculator($history6, Carbon::parse('2024-11-26'));
    $formatted = $calc6->getFormattedPrediction('fr');

    echo "✅ Dernières règles : " . $formatted['dernières_règles'] . "\n";
    echo "✅ Prochaines règles : " . $formatted['prochaines_règles'] . "\n";
    echo "✅ Prochaines règles dans : " . $formatted['prochaines_règles_dans'] . "\n";
    echo "✅ Ovulation : " . $formatted['ovulation'] . "\n";
    echo "✅ Fenêtre de fertilité : " . $formatted['fenetre_fertilité'] . "\n";
    echo "✅ TEST 6 RÉUSSI\n\n";
} catch (Exception $e) {
    echo "❌ TEST 6 ÉCHOUÉ : " . $e->getMessage() . "\n\n";
}

echo "========================================\n";
echo "✅ TOUS LES TESTS SONT TERMINÉS\n";
echo "========================================\n";
