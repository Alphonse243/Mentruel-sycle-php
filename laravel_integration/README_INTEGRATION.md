# Intégration de BioCycle Predictor dans Laravel

1. Installation
- Depuis votre projet Laravel :
```bash
composer require alphonse243/biocycle-predictor
```

2. Copier les fichiers d'exemple dans votre projet Laravel
- Controller → `app/Http/Controllers/BioCycleController.php` (copier le contenu fourni)
- Vue → `resources/views/laravel_integration/cycle.blade.php` (copier le contenu fourni)
- Route → dans `routes/web.php` ajouter :
```php
Route::match(['get','post'], '/biocycle/demo', [\App\Http\Controllers\BioCycleController::class, 'demo']);
```

3. Utilisation
- GET /biocycle/demo : affiche la démo avec données échantillon.
- POST /biocycle/demo : envoyez `last_period` et `cycles` :
  - `cycles` peut être un tableau JSON de paires start/end (Y-m-d), ou utilisez `cycles_json` (string) et parsez côté serveur.
  - Exemple payload (JSON) :
```json
{
  "last_period":"2024-12-05",
  "cycles": [
    {"start":"2024-08-15","end":"2024-09-12"},
    {"start":"2024-09-12","end":"2024-10-10"}
  ]
}
```

4. Intégration avec votre base de données
- Récupérez les enregistrements cycle (start,end) depuis votre table (ex: cycles table), construisez un tableau compatible et envoyez dans la requête au Controller.

5. Remarques
- Le Controller utilise Carbon pour parser les dates.
- Le Controller gère CycleIrregulierException et renvoie un message d'erreur dans la vue.
- Adaptez la logique (auth, récupération DB, validation) selon votre application.

6. Test rapide
```bash
php artisan serve
# Visitez http://localhost:8000/biocycle/demo
```
