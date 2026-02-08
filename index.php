<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioCycle Predictor - Prédiction de Cycle Menstruel</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #764ba2;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .button-group {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }
        a, button {
            flex: 1;
            padding: 15px 25px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }
        .btn-test {
            background: #667eea;
            color: white;
        }
        .btn-test:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .btn-demo {
            background: #764ba2;
            color: white;
        }
        .btn-demo:hover {
            background: #653a8a;
            transform: translateY(-2px);
        }
        .info-box {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
        }
        .features {
            list-style: none;
            margin-top: 20px;
        }
        .features li {
            padding: 8px 0;
            padding-left: 25px;
            position: relative;
            color: #555;
            font-size: 14px;
        }
        .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔬 BioCycle Predictor</h1>
        <p class="subtitle">Système intelligent de prédiction du cycle menstruel</p>
        
        <div class="button-group">
            <a href="test.php" class="btn-test">▶️ Exécuter les tests</a>
            <a href="demo.php" class="btn-demo">📊 Voir la démo</a>
        </div>

        <div class="info-box">
            <strong>ℹ️ À propos :</strong><br>
            BioCycle Predictor est un package PHP 8 qui calcule les prédictions de cycle menstruel avec une précision adaptative basée sur l'historique personnel.
        </div>

        <ul class="features">
            <li>Calcul de moyenne mobile sur 6 derniers cycles</li>
            <li>Détection automatique des anomalies</li>
            <li>Forçage d'ovulation (pour signes physiques)</li>
            <li>Gestion robuste des passages d'années</li>
            <li>Formatage multilingue avec Carbon</li>
        </ul>
    </div>
</body>
</html>