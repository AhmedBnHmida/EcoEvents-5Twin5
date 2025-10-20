<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propositions de Sponsoring - EcoEvents</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 11px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 20px;
        }
        
        .header p {
            color: #666;
            margin: 2px 0 0 0;
            font-size: 10px;
        }
        
        .proposal {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .proposal-header {
            background: #007bff;
            color: white;
            padding: 6px 10px;
        }
        
        .proposal-header h2 {
            margin: 0;
            font-size: 12px;
        }
        
        .proposal-content {
            padding: 10px;
        }
        
        .proposal-details {
            background: #f8f9fa;
            padding: 8px;
            border-radius: 3px;
            margin-bottom: 10px;
            font-size: 10px;
        }
        
        .proposal-details h4 {
            color: #007bff;
            margin: 0 0 5px 0;
            font-size: 11px;
        }
        
        .proposal-details .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .proposal-details .detail-label {
            font-weight: bold;
            color: #555;
        }
        
        .proposal-details .detail-value {
            color: #333;
        }
        
        .email-content {
            background: white;
            padding: 8px;
            border-left: 3px solid #007bff;
        }
        
        .email-subject {
            font-size: 11px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 8px;
        }
        
        .email-body {
            line-height: 1.4;
        }
        
        .email-body p {
            margin-bottom: 6px;
        }
        
        .email-body .greeting {
            font-weight: bold;
        }
        
        .email-body .closing {
            margin-top: 8px;
        }
        
        .email-body .signature {
            font-weight: bold;
            color: #007bff;
        }
        
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 9px;
        }
        
        .two-column {
            display: flex;
            gap: 10px;
        }
        
        .column {
            flex: 1;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏢 EcoEvents</h1>
        <p>Propositions de Sponsoring Générées par IA</p>
        <p>Date: {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="two-column">
        @foreach($proposals as $index => $proposal)
            <div class="column">
                <div class="proposal">
                    <div class="proposal-header">
                        <h2>📧 Prop. {{ $index + 1 }}: {{ $proposal['partner']['nom'] }}</h2>
                    </div>
                    
                    <div class="proposal-content">
                        <div class="proposal-details">
                            <h4>📊 Détails</h4>
                            <div class="detail-row">
                                <span class="detail-label">Événement:</span>
                                <span class="detail-value">{{ $proposal['event']['title'] }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Montant:</span>
                                <span class="detail-value"><strong>{{ number_format($proposal['allocation']['amount'], 0, ',', ' ') }}€</strong></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Type:</span>
                                <span class="detail-value">{{ ucfirst($proposal['allocation']['type']) }}</span>
                            </div>
                        </div>

                        <div class="email-content">
                            <div class="email-subject">
                                {{ $proposal['proposal']['subject'] }}
                            </div>
                            
                            <div class="email-body">
                                <p class="greeting">{{ $proposal['proposal']['greeting'] }}</p>
                                
                                <p>{{ $proposal['proposal']['introduction'] }}</p>
                                
                                <p><strong>Avantages:</strong> {{ $proposal['proposal']['benefits'] }}</p>
                                
                                <p>{{ $proposal['proposal']['call_to_action'] }}</p>
                                
                                <p class="closing">{{ $proposal['proposal']['closing'] }}</p>
                                
                                <p class="signature">{{ $proposal['proposal']['signature'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>📄 Document généré automatiquement par l'IA EcoEvents</p>
        <p>🤖 Sponsoring Builder - Optimisation intelligente des budgets</p>
        <p>📅 {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
