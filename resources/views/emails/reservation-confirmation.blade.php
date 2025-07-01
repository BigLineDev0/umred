<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de réservation</title>
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
            background-color: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 12px;
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
            margin-top: 16px;
        }
        .alert {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🧪 Confirmation de Réservation</h1>
            <div class="status-badge">{{ ucfirst(str_replace('_', ' ', $reservation->statut)) }}</div>
        </div>

        <div class="alert">
            <strong>📋 Important :</strong> Votre réservation est actuellement en attente de confirmation par l'administrateur du laboratoire.
        </div>

        <div class="details-section">
            <h2>📋 Détails de la réservation</h2>

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
                <div class="objectif-text">{{ $reservation->objectif }}</div>
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
        </div>

        <div style="text-align: center; margin-top: 32px;">
            <p><strong>Que faire maintenant ?</strong></p>
            <p>Votre demande a été transmise à l'équipe du laboratoire. Vous recevrez un email de confirmation dès que votre réservation sera validée.</p>

            <a href="{{ route('dashboard') }}" class="button">
                Voir mes réservations
            </a>
        </div>

        <div class="footer">
            <p>
                📧 Cet email a été envoyé automatiquement, merci de ne pas y répondre.<br>
                📅 Réservation effectuée le {{ $reservation->created_at->locale('fr')->isoFormat('D MMMM YYYY à HH:mm') }}
            </p>
        </div>
    </div>
</body>
</html>
