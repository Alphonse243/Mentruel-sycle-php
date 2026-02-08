<!doctype html>
<html lang="fr">
<head><meta charset="utf-8"><title>BioCycle Demo</title></head>
<body>
    <h1>Prédiction BioCycle</h1>

    @if(!empty($error))
        <div style="color: red;">Erreur : {{ $error }}</div>
    @endif

    @if(!empty($prediction))
        <p><strong>Dernières règles :</strong> {{ $prediction['dernières_règles'] ?? 'N/A' }}</p>
        <p><strong>Prochaines règles :</strong> {{ $prediction['prochaines_règles'] ?? 'N/A' }}</p>
        <p><strong>Prochaines dans :</strong> {{ $prediction['prochaines_règles_dans'] ?? 'N/A' }}</p>
        <p><strong>Ovulation :</strong> {{ $prediction['ovulation'] ?? 'N/A' }}</p>
        <p><strong>Fenêtre de fertilité :</strong> {{ $prediction['fenetre_fertilité'] ?? 'N/A' }}</p>
        <p><strong>Nombre de cycles :</strong> {{ $count }}</p>
    @endif

    <hr>

    <h2>Envoyer vos données (JSON)</h2>
    <form method="post" action="{{ url('/biocycle/demo') }}">
        @csrf
        <label>last_period (Y-m-d): <input type="text" name="last_period" value=""></label><br><br>
        <label>cycles (JSON array):</label><br>
        <textarea name="cycles" rows="6" cols="60">[{"start":"2024-08-15","end":"2024-09-12"}]</textarea><br><br>
        <button type="submit">Envoyer</button>
    </form>

    <script>
    // Transformer le textarea JSON en input utilisable côté serveur
    document.querySelector('form').addEventListener('submit', function(e){
        var txt = document.querySelector('textarea[name="cycles"]').value;
        try {
            var parsed = JSON.parse(txt);
            // créer champs cachés
            var form = this;
            // supprimer anciens
            var old = document.querySelectorAll('input[name="cycles[]"]');
            old.forEach(function(n){ n.remove(); });
            parsed.forEach(function(item){
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'cycles[]';
                input.value = JSON.stringify(item);
                form.appendChild(input);
            });
        } catch(err){
            alert('JSON invalide');
            e.preventDefault();
        }
    });
    </script>
</body>
</html>
