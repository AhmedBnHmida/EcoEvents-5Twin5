<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Contrat de Sponsoring #{{ $sponsoring->id }}</title>
    <style>
        @page {
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.6;
        }

        .page {
            padding: 40px 50px;
            position: relative;
            min-height: 100vh;
        }

        /* Header avec dégradé */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            margin: -40px -50px 30px -50px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #764ba2;
        }

        .header h1 {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header .reference {
            font-size: 14pt;
            opacity: 0.9;
            font-weight: 300;
        }

        .header .logo-placeholder {
            position: absolute;
            top: 20px;
            left: 30px;
            font-size: 10pt;
            opacity: 0.8;
        }

        /* Section d'informations */
        .info-section {
            margin: 40px 0;
        }

        .info-section h2 {
            font-size: 16pt;
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 8px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: bold;
            color: #555;
            padding: 10px 15px 10px 0;
            width: 40%;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            padding: 10px 0;
            color: #222;
            vertical-align: top;
        }

        /* Carte de partenaire */
        .partner-card {
            background: #f8f9fa;
            border-left: 5px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }

        .partner-card h3 {
            color: #667eea;
            font-size: 18pt;
            margin-bottom: 15px;
        }

        /* Highlight Box pour montant */
        .highlight-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
            margin: 30px 0;
        }

        .highlight-box .label {
            font-size: 12pt;
            opacity: 0.9;
            margin-bottom: 10px;
        }

        .highlight-box .amount {
            font-size: 36pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Badge pour type */
        .badge {
            display: inline-block;
            padding: 8px 15px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Détails de l'événement */
        .event-details {
            background: #fff3cd;
            border-left: 5px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 5px;
        }

        .event-details h3 {
            color: #856404;
            font-size: 16pt;
            margin-bottom: 15px;
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 30px;
            left: 50px;
            right: 50px;
            border-top: 2px solid #e0e0e0;
            padding-top: 20px;
            text-align: center;
            color: #888;
            font-size: 9pt;
        }

        .footer .date {
            margin-top: 10px;
            font-style: italic;
        }

        /* Divider */
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #667eea, transparent);
            margin: 30px 0;
        }

        /* Contact info avec icônes */
        .contact-item {
            padding: 8px 0;
        }

        .contact-item::before {
            content: '●';
            color: #667eea;
            margin-right: 10px;
            font-weight: bold;
        }

        /* Table style */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .details-table td:first-child {
            font-weight: bold;
            color: #555;
            width: 35%;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Watermark -->
        <div class="watermark">ECOEVENTS</div>

        <!-- Header -->
        <div class="header">
            <div class="logo-placeholder">EcoEvents Platform</div>
            <h1>Contrat de Sponsoring</h1>
            <div class="reference">Référence: SPO-{{ str_pad($sponsoring->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Montant en évidence -->
        <div class="highlight-box">
            <div class="label">MONTANT DU SPONSORING</div>
            <div class="amount">{{ number_format($sponsoring->montant, 2, ',', ' ') }} €</div>
        </div>

        <div class="divider"></div>

        <!-- Informations du Partenaire -->
        <div class="info-section">
            <h2>📋 Informations du Partenaire</h2>
            <div class="partner-card">
                @if($sponsoring->partner->logo)
                <div style="text-align: center; margin-bottom: 15px;">
                    <img src="{{ public_path('storage/' . $sponsoring->partner->logo) }}" alt="{{ $sponsoring->partner->nom }}" 
                         style="max-width: 150px; max-height: 100px; object-fit: contain;">
                </div>
                @endif
                <h3>{{ $sponsoring->partner->nom }}</h3>
                <table class="details-table">
                    <tr>
                        <td>Type de partenaire</td>
                        <td><strong>{{ $sponsoring->partner->type }}</strong></td>
                    </tr>
                    <tr>
                        <td>Personne de contact</td>
                        <td>{{ $sponsoring->partner->contact_name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $sponsoring->partner->contact_email }}</td>
                    </tr>
                    <tr>
                        <td>Téléphone</td>
                        <td>{{ $sponsoring->partner->telephone }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Détails du Sponsoring -->
        <div class="info-section">
            <h2>💼 Détails du Sponsoring</h2>
            <div class="info-grid">
                <div class="info-row">
                    <div class="info-label">Type de sponsoring :</div>
                    <div class="info-value">
                        <span class="badge">{{ $sponsoring->type_sponsoring->label() }}</span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date du contrat :</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($sponsoring->date)->format('d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Date de création :</div>
                    <div class="info-value">{{ $sponsoring->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Informations de l'Événement -->
        <div class="info-section">
            <h2>🎯 Événement Sponsorisé</h2>
            <div class="event-details">
                <h3>{{ $sponsoring->event->title }}</h3>
                <table class="details-table">
                    <tr>
                        <td>Localisation</td>
                        <td>{{ $sponsoring->event->location }}</td>
                    </tr>
                    <tr>
                        <td>Date de début</td>
                        <td>{{ \Carbon\Carbon::parse($sponsoring->event->start_date)->format('d/m/Y à H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Date de fin</td>
                        <td>{{ \Carbon\Carbon::parse($sponsoring->event->end_date)->format('d/m/Y à H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Capacité maximale</td>
                        <td>{{ $sponsoring->event->capacity_max }} participants</td>
                    </tr>
                    <tr>
                        <td>Statut</td>
                        <td><strong>{{ ucfirst($sponsoring->event->status->value) }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Description de l'événement -->
        @if($sponsoring->event->description)
        <div class="info-section">
            <h2>📝 Description de l'Événement</h2>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 5px; line-height: 1.8;">
                {{ Str::limit($sponsoring->event->description, 500) }}
            </div>
        </div>
        @endif

        <!-- Conditions -->
        <div class="info-section" style="margin-top: 40px;">
            <h2>📜 Conditions Générales</h2>
            <div style="padding: 15px; background: #f8f9fa; border-radius: 5px; font-size: 9pt; line-height: 1.8;">
                <p style="margin-bottom: 10px;">
                    <strong>1. Objet du contrat :</strong> Le présent contrat a pour objet de définir les modalités de sponsoring 
                    entre le partenaire susmentionné et l'organisation de l'événement EcoEvents.
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>2. Engagement financier :</strong> Le partenaire s'engage à verser le montant de 
                    <strong>{{ number_format($sponsoring->montant, 2, ',', ' ') }} €</strong> selon les modalités convenues.
                </p>
                <p style="margin-bottom: 10px;">
                    <strong>3. Contreparties :</strong> En échange de ce sponsoring de type <strong>{{ $sponsoring->type_sponsoring->label() }}</strong>, 
                    le partenaire bénéficiera de visibilité lors de l'événement conformément aux accords établis.
                </p>
                <p>
                    <strong>4. Durée :</strong> Le présent contrat prend effet à la date de signature et demeure valide 
                    jusqu'à la fin de l'événement et le respect de toutes les obligations contractuelles.
                </p>
            </div>
        </div>

        <!-- Signatures -->
        <div style="margin-top: 50px; display: table; width: 100%;">
            <div style="display: table-row;">
                <div style="display: table-cell; width: 50%; padding: 20px; text-align: center; border-top: 2px solid #333;">
                    <strong>Signature du Partenaire</strong><br>
                    <span style="color: #888; font-size: 9pt;">{{ $sponsoring->partner->nom }}</span><br>
                    <span style="color: #888; font-size: 9pt;">Date: _______________</span>
                </div>
                <div style="display: table-cell; width: 50%; padding: 20px; text-align: center; border-top: 2px solid #333;">
                    <strong>Signature EcoEvents</strong><br>
                    <span style="color: #888; font-size: 9pt;">Organisateur</span><br>
                    <span style="color: #888; font-size: 9pt;">Date: _______________</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div><strong>EcoEvents Platform</strong> - Plateforme d'Événements Écologiques</div>
            <div>📧 contact@ecoevents.com | 📞 +33 1 23 45 67 89 | 🌐 www.ecoevents.com</div>
            <div class="date">
                Document généré le {{ now()->format('d/m/Y à H:i:s') }}
            </div>
        </div>
    </div>
</body>
</html>

