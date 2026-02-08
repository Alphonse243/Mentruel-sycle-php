<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Définir la locale par défaut
Carbon::setLocale('fr_FR');

$hasError = false;
$errorMsg = '';
$formatted = [];
$prediction = [];
$history = new CycleHistory();

try {
    // Créer un historique de cycles réaliste
    $history->addCycle(new CycleEntity(
        Carbon::createFromFormat('Y-m-d', '2024-08-15'),
        Carbon::createFromFormat('Y-m-d', '2024-09-12')
    ));

    $history->addCycle(new CycleEntity(
        Carbon::createFromFormat('Y-m-d', '2024-09-12'),
        Carbon::createFromFormat('Y-m-d', '2024-10-10')
    ));

    $history->addCycle(new CycleEntity(
        Carbon::createFromFormat('Y-m-d', '2024-10-10'),
        Carbon::createFromFormat('Y-m-d', '2024-11-07')
    ));

    $history->addCycle(new CycleEntity(
        Carbon::createFromFormat('Y-m-d', '2024-11-07'),
        Carbon::createFromFormat('Y-m-d', '2024-12-05')
    ));

    $lastPeriod = Carbon::createFromFormat('Y-m-d', '2024-12-05');
    $calculator = new CycleCalculator($history, $lastPeriod);

    $prediction = $calculator->predictNextCycle();
    $formatted = $calculator->getFormattedPrediction('fr');
    
} catch (CycleIrregulierException $e) {
    $hasError = true;
    $errorMsg = $e->getMessage();
} catch (Exception $e) {
    $hasError = true;
    $errorMsg = 'Erreur système : ' . $e->getMessage();
    error_log('BioCycle Error: ' . $e->getTraceAsString());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Démo - BioCycle Predictor</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .card h2 {
            color: #764ba2;
            font-size: 20px;
            margin-bottom: 20px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .prediction-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        .stat-label {
            color: #666;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .stat-value {
            color: #764ba2;
            font-size: 18px;
            font-weight: 700;
        }
        .error-box {
            background: #ffe0e0;
            border-left: 4px solid #ff6b6b;
            padding: 15px;
            border-radius: 8px;
            color: #c92a2a;
            margin-top: 15px;
        }
        .history {
            margin-top: 20px;
        }
        .history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .history-item:last-child {
            border-bottom: none;
        }
        .history-label {
            color: #666;
            font-size: 14px;
        }
        .history-value {
            font-weight: 600;
            color: #764ba2;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .success-badge {
            display: inline-block;
            background: #d3f9d8;
            color: #2b8a3e;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Démo en direct</h1>
            <p>Prédictions basées sur 4 cycles historiques</p>
        </div>

        <?php if (!$hasError): ?>
            <div class="card">
                <div class="success-badge">✅ Prédiction valide</div>
                <h2>📈 Prédictions pour vos prochains cycles</h2>
                
                <div class="prediction-grid">
                    <div class="stat">
                        <div class="stat-label">Dernières règles</div>
                        <div class="stat-value"><?php echo htmlspecialchars($formatted['dernières_règles'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Prochaines règles</div>
                        <div class="stat-value"><?php echo htmlspecialchars($formatted['prochaines_règles'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Prochaines dans</div>
                        <div class="stat-value"><?php echo htmlspecialchars($formatted['prochaines_règles_dans'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="stat">
                        <div class="stat-label">Ovulation</div>
                        <div class="stat-value"><?php echo htmlspecialchars($formatted['ovulation'] ?? 'N/A'); ?></div>
                    </div>
                </div>

                <div style="margin-top: 25px;">
                    <h3 style="color: #666; font-size: 16px; margin-bottom: 12px;">🎯 Fenêtre de fertilité</h3>
                    <div class="stat" style="background: #fff3cd; border-left-color: #ffc107; margin: 0;">
                        <div class="stat-value" style="color: #856404; font-size: 16px;">
                            <?php echo htmlspecialchars($formatted['fenetre_fertilité'] ?? 'N/A'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>📊 Statistiques</h2>
                <div class="history">
                    <div class="history-item">
                        <span class="history-label">Durée moyenne du cycle</span>
                        <span class="history-value"><?php echo htmlspecialchars($formatted['durée_cycle_moyenne'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="history-item">
                        <span class="history-label">Nombre de cycles enregistrés</span>
                        <span class="history-value"><?php echo $history->count(); ?></span>
                    </div>
                    <div class="history-item">
                        <span class="history-label">Ovulation forcée</span>
                        <span class="history-value"><?php echo ($prediction['ovulation_forcée'] ?? false) ? 'OUI' : 'NON'; ?></span>
                    </div>
                    <div class="history-item">
                        <span class="history-label">Fiabilité</span>
                        <span class="history-value">🟢 Haute</span>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="card">
                <div class="error-box">
                    ⚠️ <strong>Attention :</strong> <?php echo htmlspecialchars($errorMsg); ?>
                </div>
                <p style="color: #666; margin-top: 15px;">
                    Votre cycle détecte une irrégularité. Consultez un professionnel de santé si le problème persiste.
                </p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>ℹ️ À propos de cette démo</h2>
            <p style="color: #666; line-height: 1.6;">
                Cette démo utilise 4 cycles fictifs pour montrer le fonctionnement du système. 
                L'algorithme calcule une moyenne mobile sur les 6 derniers cycles et détecte les anomalies 
                automatiquement. Vous pouvez intégrer BioCycle Predictor dans votre application en utilisant Composer.
            </p>
        </div>

        <a href="index.php" class="back-btn">← Retour à l'accueil</a>
    </div>
</body>
</html>
