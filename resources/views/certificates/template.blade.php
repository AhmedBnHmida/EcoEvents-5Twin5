<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificat de Participation - {{ $event->title }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 landscape;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #fff;
            color: #333;
            width: 100%;
            height: 100%;
        }
        .certificate-container {
            width: 100%;
            height: 100%;
            position: relative;
            background-color: #fff;
        }
        .certificate-border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #1b5e20;
            box-sizing: border-box;
        }
        .certificate-inner-border {
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            bottom: 25px;
            border: 1px solid #1b5e20;
            box-sizing: border-box;
        }
        .certificate-content {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            text-align: center;
        }
        .certificate-title {
            color: #1b5e20;
            font-size: 48px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 30px 0 10px;
            text-align: center;
        }
        .certificate-subtitle {
            color: #1b5e20;
            font-size: 28px;
            font-style: italic;
            margin: 10px 0 40px;
            text-align: center;
        }
        .certificate-text {
            color: #333;
            font-size: 20px;
            margin: 15px 0;
            text-align: center;
        }
        .recipient-name {
            color: #1b5e20;
            font-size: 36px;
            font-weight: bold;
            font-style: italic;
            margin: 20px 0;
            text-align: center;
        }
        .event-name {
            color: #1b5e20;
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .event-details {
            color: #333;
            font-size: 20px;
            font-style: italic;
            margin: 15px 0 40px;
            text-align: center;
        }
        .certificate-footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .issue-date {
            color: #333;
            font-size: 18px;
            font-style: italic;
            text-align: left;
        }
        .signature {
            text-align: center;
            margin: 0 auto;
        }
        .signature-line {
            width: 200px;
            border-top: 2px solid #1b5e20;
            margin: 10px auto;
        }
        .signature-name {
            color: #1b5e20;
            font-size: 22px;
            font-weight: bold;
            margin: 5px 0;
        }
        .signature-title {
            color: #333;
            font-size: 18px;
            font-style: italic;
        }
        .qr-code {
            text-align: right;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
            border: 2px solid #1b5e20;
            padding: 5px;
            background: white;
        }
        .verification-text {
            color: #333;
            font-size: 12px;
            margin-top: 5px;
        }
        .decorative-corner {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 2px solid #1b5e20;
        }
        .top-left {
            top: 10px;
            left: 10px;
            border-right: none;
            border-bottom: none;
        }
        .top-right {
            top: 10px;
            right: 10px;
            border-left: none;
            border-bottom: none;
        }
        .bottom-left {
            bottom: 10px;
            left: 10px;
            border-right: none;
            border-top: none;
        }
        .bottom-right {
            bottom: 10px;
            right: 10px;
            border-left: none;
            border-top: none;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="decorative-corner top-left"></div>
        <div class="decorative-corner top-right"></div>
        <div class="decorative-corner bottom-left"></div>
        <div class="decorative-corner bottom-right"></div>
        
        <div class="certificate-border"></div>
        <div class="certificate-inner-border"></div>
        
        <div class="certificate-content">
            <h1 class="certificate-title">Certificat de Participation</h1>
            <h2 class="certificate-subtitle">EcoEvents</h2>
            
            <p class="certificate-text">Ce certificat est décerné à</p>
            <p class="recipient-name">{{ $participant->name }}</p>
            
            <p class="certificate-text">pour sa participation à l'événement</p>
            <p class="event-name">{{ $event->title }}</p>
            <p class="event-details">qui s'est tenu le {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} à {{ $event->location }}</p>
            
            <div class="certificate-footer">
                <div class="issue-date">
                    <p>Délivré le {{ $issuedDate }}</p>
                </div>
                
                <div class="signature">
                    <div class="signature-line"></div>
                    <p class="signature-name">EcoEvents</p>
                    <p class="signature-title">Organisation</p>
                </div>
                
                <div class="qr-code">
                    <img src="{{ $qrCode }}" alt="QR Code de vérification">
                    <p class="verification-text">Scannez pour vérifier l'authenticité</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>