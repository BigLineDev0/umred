<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation confirmée</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .email-container {
            background-color: white;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 24px;
            margin-bottom: 32px;
        }
        .header h1 {
            color: #1f2937;
            margin: 0;
            font-size: 28px;
        }
        .status-badge {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 12px;
        }
        .confirmation-message {
            background-color: #f0f9ff;
            border: 1px solid #0ea5e9;
            color: #0c4a6e;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            text-align: center;
        }
        .confirmation-message h3 {
            margin: 0 0 8px 0;
            color: #0c4a6e;
        }
        .details-section {
            margin-bottom: 32px;
        }
        .details-section h2 {
            color: #374151;
            font-size: 20px;
            margin-bottom: 16px;
            border-left: 4px solid #3b82f6;
            padding-left: 12px;
        }
        .detail-item {
            margin-bottom: 16px;
            padding: 16px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .detail-label {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 4px;
        }
        .detail-value {
            color: #1f2937;
        }
        .equipements-list, .horaires-list {
            margin: 0;
            padding-left: 20px;
        }
        .equipements-list li, .horaires-list li {
            margin-bottom: 4px;
            color: #1f2937;
        }
        .objectif-text {
            background-color: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #6b7280;
            font-style: italic;
        }
        .footer {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 8px;
        }
        .button-secondary {
            background-color: #6b7280;
        }
        .instructions {
            background-color: #fffbeb;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .instructions h4 {
            margin: 0 0 8px 0;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>✅ Réservation Confirmée</h1>
            <div class="status-badge">Confirmée</div>
        </div>

        <div class="confirmation-message">
            <h3>🎉 Félicitations !</h3>
            <p>Votre réservation a été confirmée. Vous pouvez maintenant utiliser le laboratoire aux créneaux réservés.</p>
        </div>

        <div class="instructions">
            <h4>📝 Instructions importantes :</h4>
            <p>• Présentez-vous au laboratoire 10 minutes avant le début de votre créneau<br>
            • Apportez une pièce d'identité valide<br>
            • Respectez les règles de sécurité du laboratoire<br>
            • En cas d'empêchement, annulez votre réservation au moins 2h à l'avance</p>
        </div>

        <div class="details-section">
            <h2>📋 Détails de votre réservation</h2>

            <div class="detail-item">
                <div class="detail-label">Numéro de réservation :</div>
                <div class="detail-value"><strong>#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Laboratoire :</div>
                <div class="detail-value">{{ $reservation->laboratoire->nom }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Date de réservation :</div>
                <div class="detail-value">{{ \Carbon\Carbon::parse($reservation->date)->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Créneaux horaires :</div>
                <ul class="horaires-list">
                    @foreach($reservation->horaires as $horaire)
                        <li>{{ $horaire->heure_debut }} - {{ $horaire->heure_fin }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="detail-item">
                <div class="detail-label">Équipements réservés :</div>
                <ul class="equipements-list">
                    @foreach($reservation->equipements as $equipement)
                        <li>{{ $equipement->nom }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="detail-item">
                <div class="detail-label">Objectif :</div>
                <div class="objectif-text">{{ $reservation->objectif ?? '-' }}</div>
            </div>
        </div>

        <div class="details-section">
            <h2>👤 Informations du demandeur</h2>
            <div class="detail-item">
                <div class="detail-label">Prénom et Nom :</div>
                <div class="detail-value">{{ $reservation->user->prenom }} {{ $reservation->user->name }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Email :</div>
                <div class="detail-value">{{ $reservation->user->email }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Téléphone :</div>
                <div class="detail-value">{{ $reservation->user->telephone ?? '-' }}</div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 32px;">
            <a href="{{ route('dashboard') }}" class="button">
                Gérer mes réservations
            </a>
            <a href="{{ route('laboratoires')}}" class="button button-secondary">
                Voir le laboratoire
            </a>
        </div>

        <div class="footer">
            <p>
                📧 Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                📅 Réservation confirmée le {{ $reservation->created_at->locale('fr')->isoFormat('D MMMM YYYY à HH:mm') }}<br>
                ❓ Besoin d'aide ? Contactez-nous via votre tableau de bord.
            </p>
        </div>
    </div>
</body>
</html>
