<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificat de Participation - {{ $event->title }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #f9f9f9;
            color: #333;
            width: 100%;
            height: 100%;
        }
        .certificate-container {
            width: 100%;
            height: 100%;
            padding: 0;
            box-sizing: border-box;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .certificate-frame {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
            background: #fff;
        }
        .certificate-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(135deg, rgba(245, 247, 250, 0.92) 0%, rgba(228, 239, 233, 0.92) 100%),
                url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23205723' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E"),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23104a1c' fill-opacity='0.02'%3E%3Cpath d='M0 0h80v80H0V0zm20 20v40h40V20H20zm20 35a15 15 0 1 1 0-30 15 15 0 0 1 0 30z' opacity='.5'/%3E%3Cpath d='M15 15h50l-5 5H20v40l-5 5V15zm0 50h50V15L80 0v80H0l15-15zm32.07-32.07l3.54-3.54A15 15 0 0 1 29.4 50.6l3.53-3.53a10 10 0 1 0 14.14-14.14zM32.93 47.07a10 10 0 1 1 14.14-14.14L32.93 47.07z'/%3E%3C/g%3E%3C/svg%3E");
            z-index: 0;
        }
        .certificate-border {
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            bottom: 25px;
            border: 6px double #1b5e20;
            background-color: rgba(255, 255, 255, 0.97);
            z-index: 1;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
        }
        .certificate-inner-border {
            position: absolute;
            top: 35px;
            left: 35px;
            right: 35px;
            bottom: 35px;
            border: 1px solid rgba(27, 94, 32, 0.3);
            z-index: 1;
        }
        .certificate-ornament {
            position: absolute;
            width: 60px;
            height: 60px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            z-index: 2;
            opacity: 0.5;
        }
        .certificate-ornament.top-left {
            top: 45px;
            left: 45px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 24 24' fill='none' stroke='%231b5e20' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 2L7 7M7 7L12 12M7 7L2 7M7 7L7 2'/%3E%3Cpath d='M12 22L7 17M7 17L12 12M7 17L2 17M7 17L7 22'/%3E%3Cpath d='M22 12L17 7M17 7L12 12M17 7L17 2M17 7L22 7'/%3E%3Cpath d='M22 12L17 17M17 17L12 12M17 17L17 22M17 17L22 17'/%3E%3C/svg%3E");
        }
        .certificate-ornament.top-right {
            top: 45px;
            right: 45px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 24 24' fill='none' stroke='%231b5e20' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 2L7 7M7 7L12 12M7 7L2 7M7 7L7 2'/%3E%3Cpath d='M12 22L7 17M7 17L12 12M7 17L2 17M7 17L7 22'/%3E%3Cpath d='M22 12L17 7M17 7L12 12M17 7L17 2M17 7L22 7'/%3E%3Cpath d='M22 12L17 17M17 17L12 12M17 17L17 22M17 17L22 17'/%3E%3C/svg%3E");
        }
        .certificate-ornament.bottom-left {
            bottom: 45px;
            left: 45px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 24 24' fill='none' stroke='%231b5e20' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 2L7 7M7 7L12 12M7 7L2 7M7 7L7 2'/%3E%3Cpath d='M12 22L7 17M7 17L12 12M7 17L2 17M7 17L7 22'/%3E%3Cpath d='M22 12L17 7M17 7L12 12M17 7L17 2M17 7L22 7'/%3E%3Cpath d='M22 12L17 17M17 17L12 12M17 17L17 22M17 17L22 17'/%3E%3C/svg%3E");
        }
        .certificate-ornament.bottom-right {
            bottom: 45px;
            right: 45px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 24 24' fill='none' stroke='%231b5e20' stroke-width='1' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 2L7 7M7 7L12 12M7 7L2 7M7 7L7 2'/%3E%3Cpath d='M12 22L7 17M7 17L12 12M7 17L2 17M7 17L7 22'/%3E%3Cpath d='M22 12L17 7M17 7L12 12M17 7L17 2M17 7L22 7'/%3E%3Cpath d='M22 12L17 17M17 17L12 12M17 17L17 22M17 17L22 17'/%3E%3C/svg%3E");
        }
        .certificate-content {
            position: relative;
            z-index: 2;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 60px;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .header h1 {
            color: #1b5e20;
            font-size: 52px;
            margin: 0;
            padding: 0;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-weight: bold;
            text-shadow: 2px 2px 3px rgba(0, 0, 0, 0.1);
            font-family: 'Georgia', 'Times New Roman', serif;
            position: relative;
            display: inline-block;
        }
        .header h1:before, .header h1:after {
            content: "★";
            font-size: 24px;
            color: #1b5e20;
            opacity: 0.6;
            position: relative;
            top: -10px;
            margin: 0 15px;
        }
        .header h2 {
            color: #2e7d32;
            font-size: 28px;
            margin: 10px 0 0;
            padding: 0;
            font-weight: 500;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
        }
        .header:after {
            content: '';
            display: block;
            width: 250px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #1b5e20, transparent);
            margin: 15px auto;
        }
        .content {
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px 0;
        }
        .recipient {
            font-size: 46px;
            font-weight: bold;
            margin: 25px 0;
            color: #1b5e20;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.08);
            position: relative;
            display: inline-block;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
            padding: 0 20px;
        }
        .recipient:before, .recipient:after {
            content: '~';
            position: absolute;
            font-size: 36px;
            color: #1b5e20;
            opacity: 0.6;
            top: 50%;
            transform: translateY(-50%);
        }
        .recipient:before {
            left: -15px;
        }
        .recipient:after {
            right: -15px;
        }
        .recipient-underline {
            display: block;
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #1b5e20, transparent);
            margin: 10px auto;
        }
        .description {
            font-size: 24px;
            line-height: 1.6;
            margin: 15px auto;
            max-width: 80%;
            color: #33691e;
            font-family: 'Georgia', 'Times New Roman', serif;
        }
        .event-details {
            margin: 20px 0;
            font-size: 32px;
            color: #1b5e20;
            font-weight: bold;
            font-family: 'Georgia', 'Times New Roman', serif;
        }
        .event-location {
            font-size: 22px;
            color: #33691e;
            margin-top: 15px;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
        }
        .date {
            font-size: 18px;
            color: #33691e;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
        }
        .signature {
            text-align: center;
            margin: 0 auto;
        }
        .signature-line {
            width: 250px;
            border-top: 2px solid #1b5e20;
            margin: 10px auto;
        }
        .signature-name {
            font-weight: bold;
            font-size: 22px;
            color: #1b5e20;
            font-family: 'Georgia', 'Times New Roman', serif;
        }
        .signature-title {
            font-size: 18px;
            color: #33691e;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
        }
        .qr-code {
            text-align: right;
        }
        .qr-code img {
            width: 130px;
            height: 130px;
            border: 3px solid #1b5e20;
            padding: 5px;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
        }
        .qr-code:before {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(27, 94, 32, 0.05);
            border-radius: 5px;
            top: 50%;
            right: 50%;
            transform: translate(50%, -50%) rotate(45deg);
            z-index: 1;
        }
        .verification-text {
            font-size: 12px;
            color: #33691e;
            margin-top: 8px;
            text-align: center;
            font-family: 'Georgia', 'Times New Roman', serif;
            font-style: italic;
            position: relative;
            z-index: 2;
        }
        .eco-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            opacity: 0.08;
            pointer-events: none;
        }
        .leaf {
            position: absolute;
            font-size: 60px;
            color: #1b5e20;
        }
        .leaf:nth-child(1) { top: 10%; left: 5%; transform: rotate(-15deg); }
        .leaf:nth-child(2) { top: 25%; right: 8%; transform: rotate(15deg); }
        .leaf:nth-child(3) { bottom: 20%; left: 10%; transform: rotate(25deg); }
        .leaf:nth-child(4) { bottom: 30%; right: 5%; transform: rotate(-20deg); }
        .leaf:nth-child(5) { top: 50%; left: 15%; transform: rotate(10deg); }
        .leaf:nth-child(6) { top: 60%; right: 15%; transform: rotate(-10deg); }
        
        .certificate-seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 160px;
            height: 160px;
            background: rgba(27, 94, 32, 0.05);
            border: 3px solid #1b5e20;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transform: rotate(-15deg);
            z-index: 1;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .certificate-seal:before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: radial-gradient(circle, transparent 50%, rgba(27, 94, 32, 0.03) 100%);
            z-index: -1;
        }
        .certificate-seal-inner {
            width: 130px;
            height: 130px;
            border: 1px dashed #1b5e20;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #1b5e20;
            font-size: 16px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: 'Georgia', 'Times New Roman', serif;
            position: relative;
        }
        .certificate-seal-inner:before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 1px solid rgba(27, 94, 32, 0.2);
            border-radius: 50%;
        }
        .corner-decoration {
            position: absolute;
            width: 120px;
            height: 120px;
            z-index: 1;
        }
        .corner-decoration.top-left {
            top: 40px;
            left: 40px;
            border-top: 4px solid #1b5e20;
            border-left: 4px solid #1b5e20;
            border-top-left-radius: 20px;
        }
        .corner-decoration.top-right {
            top: 40px;
            right: 40px;
            border-top: 4px solid #1b5e20;
            border-right: 4px solid #1b5e20;
            border-top-right-radius: 20px;
        }
        .corner-decoration.bottom-left {
            bottom: 40px;
            left: 40px;
            border-bottom: 4px solid #1b5e20;
            border-left: 4px solid #1b5e20;
            border-bottom-left-radius: 20px;
        }
        .corner-decoration.bottom-right {
            bottom: 40px;
            right: 40px;
            border-bottom: 4px solid #1b5e20;
            border-right: 4px solid #1b5e20;
            border-bottom-right-radius: 20px;
        }
        .decorative-line {
            position: absolute;
            background: rgba(27, 94, 32, 0.1);
            z-index: 1;
        }
        .decorative-line.horizontal {
            height: 3px;
            left: 60px;
            right: 60px;
        }
        .decorative-line.vertical {
            width: 3px;
            top: 60px;
            bottom: 60px;
        }
        .decorative-line.top {
            top: 60px;
        }
        .decorative-line.bottom {
            bottom: 60px;
        }
        .decorative-line.left {
            left: 60px;
        }
        .decorative-line.right {
            right: 60px;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-frame">
            <div class="certificate-background"></div>
            <div class="certificate-border"></div>
            <div class="certificate-inner-border"></div>
            
            <div class="decorative-line horizontal top"></div>
            <div class="decorative-line horizontal bottom"></div>
            <div class="decorative-line vertical left"></div>
            <div class="decorative-line vertical right"></div>
            
            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>
            
            <div class="certificate-ornament top-left"></div>
            <div class="certificate-ornament top-right"></div>
            <div class="certificate-ornament bottom-left"></div>
            <div class="certificate-ornament bottom-right"></div>
            
            <div class="eco-elements">
                <div class="leaf">🍃</div>
                <div class="leaf">🌿</div>
                <div class="leaf">🌱</div>
                <div class="leaf">🍀</div>
                <div class="leaf">🌿</div>
                <div class="leaf">🍃</div>
            </div>
            
            <div class="certificate-content">
                <div class="header">
                    <h1>Certificat de Participation</h1>
                    <h2>EcoEvents</h2>
                </div>
                
                <div class="content">
                    <p class="description">Ce certificat est décerné à</p>
                    <p class="recipient">{{ $participant->name }}</p>
                    <div class="recipient-underline"></div>
                    <p class="description">pour sa participation à l'événement</p>
                    <p class="event-details">{{ $event->title }}</p>
                    <p class="event-location">qui s'est tenu le {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }} à {{ $event->location }}</p>
                </div>
                
                <div class="footer">
                    <div class="date">
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
                
                <div class="certificate-seal">
                    <div class="certificate-seal-inner">Certifié<br>EcoEvents</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
