#  BioCycle Predictor

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)](https://www.php.net/)
[![Composer](https://img.shields.io/badge/Composer-Compatible-green)](https://getcomposer.org/)

Un package PHP 8 intelligent et adaptatif pour la prédiction du cycle menstruel avec détection d'anomalies.

##  Caractéristiques

-  **Calcul adaptatif** : Moyenne mobile sur les 6 derniers cycles
-  **Détection d'anomalies** : Alerte si écart > 7 jours
-  **Ovulation intelligente** : Forçage manuel possible
-  **Gestion robuste** : Passages d'années, fuseaux horaires
-  **Formatage multilingue** : Français, anglais, etc.
-  **Tests unitaires** : 100% de couverture

##  Installation

```bash
composer require alphonse243/biocycle-predictor
```

##  Utilisation rapide

```php
<?php
require_once 'vendor/autoload.php';

use Alphonse243\BioCycle\Calculator\CycleCalculator;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Carbon\Carbon;

// Créer un historique
$history = new CycleHistory();

$history->addCycle(new CycleEntity(
    Carbon::createFromFormat('Y-m-d', '2024-10-01'),
    Carbon::createFromFormat('Y-m-d', '2024-10-29')
));

$history->addCycle(new CycleEntity(
    Carbon::createFromFormat('Y-m-d', '2024-10-29'),
    Carbon::createFromFormat('Y-m-d', '2024-11-26')
));

// Créer le calculateur
$calculator = new CycleCalculator(
    $history,
    Carbon::createFromFormat('Y-m-d', '2024-11-26')
);

// Obtenir les prédictions
$formatted = $calculator->getFormattedPrediction('fr');

echo "Prochaines règles : " . $formatted['prochaines_règles'];
echo "Fenêtre de fertilité : " . $formatted['fenetre_fertilité'];
```

##  Documentation complète

### Architecture

Le package est organisé en trois entités principales :

#### CycleEntity
Objet simple représentant un cycle passé.

```php
$cycle = new CycleEntity(
    Carbon::createFromFormat('Y-m-d', '2024-10-01'),
    Carbon::createFromFormat('Y-m-d', '2024-10-29')
);

echo $cycle->getDureeRecue(); // 28 jours
```

#### CycleHistory
Collection d'objets CycleEntity avec analyse statistique.

```php
$history = new CycleHistory();
$history->addCycle($cycle1);
$history->addCycle($cycle2);

$moyenne = $history->getAverageDuration(); // float
echo $history->count(); // int
```

#### CycleCalculator
Cœur du système : calculs de prédiction avec logique adaptative.

```php
$calculator = new CycleCalculator($history, $lastPeriodDate);

// Prédictions brutes
$prediction = $calculator->predictNextCycle();

// Prédictions formatées
$formatted = $calculator->getFormattedPrediction('fr');
```

### Méthodes principales

#### forceOvulationDate()
Permet de forcer une date d'ovulation si détection physique.

```php
$forcedDate = Carbon::now()->addDays(5);
$calculator->forceOvulationDate($forcedDate);
$prediction = $calculator->predictNextCycle();
```

#### Gestion des exceptions

```php
use Alphonse243\BioCycle\Exception\CycleIrregulierException;

try {
    $prediction = $calculator->predictNextCycle();
} catch (CycleIrregulierException $e) {
    echo "⚠️ Cycle irrégulier détecté : " . $e->getMessage();
}
```

##  Tests

```bash
composer test
composer test-coverage
```

## 📄 License

MIT License - Voir [LICENSE](LICENSE) pour plus de détails.

##  Auteur

**Katumba Tchibambe Alphonse**
- GitHub: [@alphonse243](https://github.com/alphonse243)
- Email: alphonse@example.com
