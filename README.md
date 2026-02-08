# 🔬 BioCycle Predictor

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.0-blue)](https://www.php.net/)
[![Composer](https://img.shields.io/badge/Composer-Compatible-green)](https://getcomposer.org/)

Un package PHP 8 intelligent et adaptatif pour la prédiction du cycle menstruel avec détection d'anomalies.

## 📋 Table des matières

- [Caractéristiques](#caractéristiques)
- [Installation](#installation)
- [Utilisation rapide](#utilisation-rapide)
- [Documentation détaillée](#documentation-détaillée)
- [API Référence](#api-référence)
- [Tests](#tests)
- [Contribuer](#contribuer)
- [License](#license)

## ✨ Caractéristiques

- ✅ **Calcul adaptatif** : Moyenne mobile sur les 6 derniers cycles
- ✅ **Détection d'anomalies** : Alerte si écart > 7 jours par rapport à la moyenne
- ✅ **Ovulation intelligente** : Forçage manuel possible si détection physique
- ✅ **Gestion robuste** : Passages d'années, fuseaux horaires, locales
- ✅ **Formatage multilingue** : Support du français et autres langues
- ✅ **Exception handling** : Gestion d'erreurs complète
- ✅ **Tests unitaires** : 100% de couverture avec PHPUnit

## 📦 Installation

### Via Composer

```bash
composer require alphonse243/biocycle-predictor
```

### Installation manuelle

```bash
git clone https://github.com/alphonse243/biocycle-predictor.git
cd biocycle-predictor
composer install
```

## 🚀 Utilisation rapide

### Exemple basique

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

### Avec gestion d'erreur

```php
<?php
use Alphonse243\BioCycle\Exception\CycleIrregulierException;

try {
    $prediction = $calculator->predictNextCycle();
} catch (CycleIrregulierException $e) {
    echo "⚠️ Cycle irrégulier détecté : " . $e->getMessage();
}
```

### Forcer une date d'ovulation

```php
<?php
$forcedOvulationDate = Carbon::now()->addDays(5);
$calculator->forceOvulationDate($forcedOvulationDate);
$prediction = $calculator->predictNextCycle();
```

## 📚 Documentation détaillée

### Architecture

Le package est organisé en trois entités principales :

#### 1. CycleEntity

Objet simple représentant un cycle passé.

```php
$cycle = new CycleEntity(
    Carbon::createFromFormat('Y-m-d', '2024-10-01'),
    Carbon::createFromFormat('Y-m-d', '2024-10-29')
);

echo $cycle->getDureeRecue(); // 28 jours
echo $cycle->getDateDebut(); // Carbon instance
echo $cycle->getDateFin(); // Carbon instance
```

#### 2. CycleHistory

Collection d'objets CycleEntity avec analyse statistique.

```php
$history = new CycleHistory();

// Ajouter des cycles
$history->addCycle($cycle1);
$history->addCycle($cycle2);
$history->addCycle($cycle3);

// Obtenir la moyenne des 6 derniers cycles
$moyenne = $history->getAverageDuration(); // float

// Compter les cycles
echo $history->count(); // int
```

#### 3. CycleCalculator

Cœur du système : calculs de prédiction avec logique adaptative.

```php
$calculator = new CycleCalculator($history, $lastPeriodDate);

// Prédictions brutes (objets Carbon)
$prediction = $calculator->predictNextCycle();
/*
[
    'dernières_règles' => Carbon,
    'prochaines_règles' => Carbon,
    'ovulation' => Carbon,
    'fenetre_fertilité_début' => Carbon,
    'fenetre_fertilité_fin' => Carbon,
    'durée_cycle_moyenne' => float,
    'ovulation_forcée' => bool
]
*/

// Prédictions formatées pour l'utilisateur
$formatted = $calculator->getFormattedPrediction('fr');
/*
[
    'dernières_règles' => '01 novembre 2024',
    'prochaines_règles' => '29 novembre 2024',
    'prochaines_règles_dans' => 'dans 3 jours',
    'ovulation' => '15 novembre 2024',
    'fenetre_fertilité' => 'du 11 au 16 novembre 2024',
    'durée_cycle_moyenne' => '28 jours',
    'ovulation_forcée' => false
]
*/
```

### Règles métier (Business Rules)

#### RÈGLE A : Moyenne mobile adaptative
- Calcul sur les **6 derniers cycles** pour s'adapter à l'évolution du corps
- Permet une prédiction plus précise que la moyenne globale
- Ignore les cycles trop anciens

#### RÈGLE B : Détection d'anomalie
- Si `|cycle_actuel - moyenne| > 7 jours`, une `CycleIrregulierException` est levée
- Alerte l'utilisateur d'une irrégularité significative
- Recommande une consultation médicale

#### RÈGLE C : Priorité biologique
- La méthode `forceOvulationDate()` permet de surcharger les calculs statistiques
- Útile si l'utilisateur détecte des signes physiques d'ovulation
- Conserve la trace de cette intervention manuelle

### Gestion des dates

Le package gère automatiquement :

```php
// Passages d'années
$cycle = new CycleEntity(
    Carbon::createFromFormat('Y-m-d', '2023-12-15'),
    Carbon::createFromFormat('Y-m-d', '2024-01-12')
);

// Fuseaux horaires (via Carbon)
Carbon::setTimezone('Europe/Paris');

// Locales (français, anglais, etc.)
$formatted = $calculator->getFormattedPrediction('fr');
$formatted = $calculator->getFormattedPrediction('en');
```

## 🔌 API Référence

### CycleEntity

```php
class CycleEntity {
    public function __construct(Carbon $dateDebut, Carbon $dateFin)
    public function getDateDebut(): Carbon
    public function getDateFin(): Carbon
    public function getDureeRecue(): int
}
```

### CycleHistory

```php
class CycleHistory {
    public function addCycle(CycleEntity $cycle): self
    public function getCycles(): array
    public function getAverageDuration(int $lastNcycles = 6): float
    public function count(): int
}
```

### CycleCalculator

```php
class CycleCalculator {
    public function __construct(CycleHistory $history, Carbon $lastPeriodDate)
    public function forceOvulationDate(Carbon $date): self
    public function predictNextCycle(): array
    public function getFormattedPrediction(string $locale = 'fr'): array
}
```

### CycleIrregulierException

```php
class CycleIrregulierException extends Exception {
    public function __construct(string $message = "Cycle irrégulier détecté")
}
```

## 🧪 Tests

### Exécuter tous les tests

```bash
composer test
# Ou directement :
./vendor/bin/phpunit tests/
```

### Résultats attendus

```
OK (5 tests, 12 assertions)

✅ Test 1 : Cycle parfait de 28 jours
✅ Test 2 : Recalcul de moyenne après cycle irrégulier
✅ Test 3 : Gestion des passages d'années
✅ Test 4 : Exception pour cycle irrégulier
✅ Test 5 : Ovulation forcée écrase les calculs
```

### Tests manuels

```bash
# Exécuter l'exemple CLI
php exemple_utilisation.php

# Lancer le serveur de test
php test.php

# Voir la démo web
php -S localhost:8000
# Visitez : http://localhost:8000/demo.php
```

## 🤝 Contribuer

Les contributions sont bienvenues ! 

1. Fork le projet
2. Créez une branche (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📄 License

Ce projet est sous license MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 👨‍💻 Auteur

**Katumba Tchibambe Alphonse**

- GitHub: [@alphonse243](https://github.com/alphonse243)
- Email: alphonse@example.com

## 🙏 Remerciements

- [Carbon](https://carbon.nesbot.com/) pour la manipulation des dates
- [PHPUnit](https://phpunit.de/) pour les tests
- [Packagist](https://packagist.org/) pour la distribution

---

**Besoin d'aide ?** Ouvrez une [issue](https://github.com/alphonse243/biocycle-predictor/issues) ou une [discussion](https://github.com/alphonse243/biocycle-predictor/discussions).
