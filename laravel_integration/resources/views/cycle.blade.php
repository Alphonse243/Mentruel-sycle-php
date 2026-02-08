<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>BioCycle Demo</title>
</head>
<body>
	<h1>Prédiction BioCycle</h1>

	@if(!empty($error))
		<div style="color: #b71c1c;"><strong>Erreur :</strong> {{ $error }}</div>
	@endif

	@if(!empty($prediction))
		<ul>
			<li><strong>Dernières règles :</strong> {{ $prediction['dernières_règles'] ?? 'N/A' }}</li>
			<li><strong>Prochaines règles :</strong> {{ $prediction['prochaines_règles'] ?? 'N/A' }}</li>
			<li><strong>Prochaines dans :</strong> {{ $prediction['prochaines_règles_dans'] ?? 'N/A' }}</li>
			<li><strong>Ovulation :</strong> {{ $prediction['ovulation'] ?? 'N/A' }}</li>
			<li><strong>Fenêtre de fertilité :</strong> {{ $prediction['fenetre_fertilité'] ?? 'N/A' }}</li>
			<li><strong>Durée moyenne :</strong> {{ $prediction['durée_cycle_moyenne'] ?? 'N/A' }}</li>
		</ul>
	@endif

	<p><strong>Cycles enregistrés :</strong> {{ $count ?? 0 }}</p>

	<hr>

	<h2>Tester avec vos données</h2>
	<form method="post" action="{{ url('/biocycle/demo') }}">
		@csrf
		<label>Dernières règles (YYYY-MM-DD) : <input type="text" name="last_period" value=""></label><br><br>

		<p>Envoyer un JSON simple (exemple) via champ "cycles_json" :</p>
		<textarea name="cycles_json" rows="6" cols="60">[{"start":"2024-08-15","end":"2024-09-12"},{"start":"2024-09-12","end":"2024-10-10"}]</textarea><br>
		<p>Ou envoyez via clé 'cycles' (en JS/fetch ou via formulaire encodé si nécessaire).</p>

		<button type="submit">Envoyer</button>
	</form>

	<script>
		// Optionnel : soumettre le textarea en tant que champ 'cycles' JSON parsé côté serveur si nécessaire.
	</script>
</body>
</html>
