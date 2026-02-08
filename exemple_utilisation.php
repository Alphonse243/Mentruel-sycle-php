<?php

// Vérifier que Composer est installé
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    die('❌ Erreur : Composer n\'est pas installé. Exécutez : composer install');
}

require_once __DIR__ . '/vendor/autoload.php';

use Alphonse243\BioCycle\Calculator\CycleCalculator;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Alphonse243\BioCycle\Exception\CycleIrregulierException;
use Carbon\Carbon;

try {
    // Créer un historique de cycles
    $history = new CycleHistory();

    $history->addCycle(new CycleEntity(
        Carbon::parse('2024-10-01'),
        Carbon::parse('2024-10-29')
    ));

    $history->addCycle(new CycleEntity(
        Carbon::parse('2024-10-29'),
        Carbon::parse('2024-11-26')
    ));

    $history->addCycle(new CycleEntity(
        Carbon::parse('2024-11-26'),
        Carbon::parse('2024-12-24')
    ));

    // Créer le calculateur avec la date des dernières règles
    $calculator = new CycleCalculator($history, Carbon::parse('2024-12-24'));

    // Obtenir les prédictions formatées pour l'utilisateur
    $formatted = $calculator->getFormattedPrediction('fr');
    
    echo "=== PRÉDICTIONS DE CYCLE MENSTRUEL ===\n";
    echo "Dernières règles : " . $formatted['dernières_règles'] . "\n";
    echo "Prochaines règles : " . $formatted['prochaines_règles'] . "\n";
    echo "Prochaines règles " . $formatted['prochaines_règles_dans'] . "\n";
    echo "Ovulation : " . $formatted['ovulation'] . "\n";
    echo "Fenêtre de fertilité : " . $formatted['fenetre_fertilité'] . "\n";
    echo "Durée moyenne du cycle : " . $formatted['durée_cycle_moyenne'] . "\n";
    
} catch (CycleIrregulierException $e) {
    echo "⚠️ Attention : " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur système : " . $e->getMessage() . "\n";
    echo "Stack trace : " . $e->getTraceAsString() . "\n";
}
