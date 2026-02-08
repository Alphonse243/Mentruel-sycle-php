<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>BioCycle Demo</title></head>
<body>
    <h1>Prédiction BioCycle</h1>
    <p><strong>Dernières règles :</strong> {{ $prediction['dernières_règles'] ?? 'N/A' }}</p>
    <p><strong>Prochaines règles :</strong> {{ $prediction['prochaines_règles'] ?? 'N/A' }}</p>
    <p><strong>Prochaines dans :</strong> {{ $prediction['prochaines_règles_dans'] ?? 'N/A' }}</p>
    <p><strong>Ovulation :</strong> {{ $prediction['ovulation'] ?? 'N/A' }}</p>
    <p><strong>Fenêtre de fertilité :</strong> {{ $prediction['fenetre_fertilité'] ?? 'N/A' }}</p>
    <p><strong>Nombre de cycles :</strong> {{ $count }}</p>
</body>
</html>
