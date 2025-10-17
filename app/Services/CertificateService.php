<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Registration;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateService
{
    /**
     * Generate a certificate for a registration
     *
     * @param Registration $registration
     * @return Certificate
     */
    public function generateCertificate(Registration $registration): Certificate
    {
        // Check if certificate already exists
        if ($registration->certificate) {
            return $registration->certificate;
        }

        // Generate certificate only if registration is for a completed event and participant attended
        if ($registration->status->value !== 'attended' || $registration->event->status->value !== 'COMPLETED') {
            throw new \Exception('Cannot generate certificate: participant did not attend or event is not completed');
        }

        try {
            // Try to generate QR code for certificate verification
            $verificationCode = $this->generateVerificationQrCode($registration);
            
            // Try to generate PDF
            $pdf = $this->generatePdf($registration, $verificationCode);
            
            // Save PDF or HTML to storage
            $fileExtension = (extension_loaded('gd') && extension_loaded('imagick')) ? 'pdf' : 'html';
            $filePath = 'certificates/' . $registration->ticket_code . '_certificate.' . $fileExtension;
            Storage::disk('public')->put($filePath, $pdf->output());
        } catch (\Exception $e) {
            // If everything fails, create a simple HTML certificate as a fallback
            $html = $this->generateSimpleHtmlCertificate($registration);
            $filePath = 'certificates/' . $registration->ticket_code . '_certificate.html';
            Storage::disk('public')->put($filePath, $html);
        }
        
        // Create certificate record regardless of format
        $certificate = Certificate::create([
            'registration_id' => $registration->id,
            'file_path' => $filePath,
            'generated_at' => now(),
            'download_count' => 0,
        ]);
        
        return $certificate;
    }
    
    /**
     * Generate a verification QR code for the certificate
     *
     * @param Registration $registration
     * @return string Base64 encoded QR code image
     */
    private function generateVerificationQrCode(Registration $registration): string
    {
        $verificationData = [
            'certificate_type' => 'attendance',
            'event_id' => $registration->event_id,
            'event_title' => $registration->event->title,
            'participant_id' => $registration->user_id,
            'participant_name' => $registration->user->name,
            'issued_at' => now()->toIso8601String(),
            'verification_code' => md5($registration->id . $registration->user_id . $registration->event_id . config('app.key')),
        ];
        
        try {
            // Try to use PNG format first (requires imagick)
            $qrCode = QrCode::format('png')
                ->size(200)
                ->errorCorrection('H')
                ->generate(json_encode($verificationData));
                
            return 'data:image/png;base64,' . base64_encode($qrCode);
        } catch (\Exception $e) {
            // Fallback to SVG format which doesn't require imagick
            $qrCode = QrCode::format('svg')
                ->size(200)
                ->errorCorrection('H')
                ->generate(json_encode($verificationData));
                
            return 'data:image/svg+xml;base64,' . base64_encode($qrCode);
        }
    }
    
    /**
     * Generate the PDF certificate
     *
     * @param Registration $registration
     * @param string $qrCodeImage
     * @return \Barryvdh\DomPDF\PDF
     */
    private function generatePdf(Registration $registration, string $qrCodeImage)
    {
        try {
            $data = [
                'registration' => $registration,
                'event' => $registration->event,
                'participant' => $registration->user,
                'qrCode' => $qrCodeImage,
                'issuedDate' => now()->format('d/m/Y'),
            ];
            
            $pdf = PDF::loadView('certificates.template', $data);
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);
            
            return $pdf;
        } catch (\Exception $e) {
            // If PDF generation fails, create a simple HTML certificate instead
            $html = $this->generateSimpleHtmlCertificate($registration);
            
            // Store the HTML content as a file
            $filePath = 'certificates/' . $registration->ticket_code . '_certificate.html';
            Storage::disk('public')->put($filePath, $html);
            
            // Return a mock PDF object that has an output method
            return new class($html) {
                private $html;
                
                public function __construct($html) {
                    $this->html = $html;
                }
                
                public function output() {
                    return $this->html;
                }
            };
        }
    }
    
    /**
     * Generate a simple HTML certificate as a fallback
     *
     * @param Registration $registration
     * @return string
     */
    private function generateSimpleHtmlCertificate(Registration $registration)
    {
        $event = $registration->event;
        $participant = $registration->user;
        $issuedDate = now()->format('d/m/Y');
        
        // Generate QR code for verification
        try {
            $qrCodeImage = $this->generateVerificationQrCode($registration);
        } catch (\Exception $e) {
            // If QR code generation fails, create a placeholder
            $qrCodeImage = "data:image/svg+xml;base64," . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#ffffff" stroke="#1b5e20" stroke-width="2"/><text x="100" y="100" font-family="Arial" font-size="14" text-anchor="middle" fill="#1b5e20">QR Code</text></svg>');
        }
        
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Certificat de Participation - {$event->title}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f9f9f9;
                    color: #333;
                }
                .certificate {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 40px;
                    background-color: #fff;
                    border: 10px solid #1b5e20;
                    border-radius: 10px;
                    text-align: center;
                }
                .header {
                    margin-bottom: 40px;
                }
                .title {
                    font-size: 36px;
                    color: #1b5e20;
                    margin-bottom: 10px;
                    text-transform: uppercase;
                }
                .subtitle {
                    font-size: 24px;
                    color: #2e7d32;
                    margin-bottom: 30px;
                }
                .content {
                    margin-bottom: 40px;
                }
                .recipient {
                    font-size: 32px;
                    font-weight: bold;
                    color: #1b5e20;
                    margin: 20px 0;
                }
                .description {
                    font-size: 18px;
                    margin: 10px 0;
                    color: #33691e;
                }
                .event-details {
                    font-size: 24px;
                    font-weight: bold;
                    color: #1b5e20;
                    margin: 15px 0;
                }
                .event-location {
                    font-size: 18px;
                    color: #33691e;
                    margin: 10px 0 30px;
                }
                .footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-end;
                    margin-top: 60px;
                }
                .date {
                    font-size: 16px;
                    color: #33691e;
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
                    font-weight: bold;
                    font-size: 18px;
                    color: #1b5e20;
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
                }
                .verification-text {
                    font-size: 12px;
                    color: #33691e;
                    margin-top: 8px;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="certificate">
                <div class="header">
                    <h1 class="title">Certificat de Participation</h1>
                    <h2 class="subtitle">EcoEvents</h2>
                </div>
                
                <div class="content">
                    <p class="description">Ce certificat est décerné à</p>
                    <p class="recipient">{$participant->name}</p>
                    <p class="description">pour sa participation à l'événement</p>
                    <p class="event-details">{$event->title}</p>
                    <p class="event-location">qui s'est tenu le {$event->start_date->format('d/m/Y')} à {$event->location}</p>
                </div>
                
                <div class="footer">
                    <div class="date">
                        <p>Délivré le {$issuedDate}</p>
                    </div>
                    
                    <div class="signature">
                        <div class="signature-line"></div>
                        <p class="signature-name">EcoEvents</p>
                    </div>
                    
                    <div class="qr-code">
                        <img src="{$qrCodeImage}" alt="QR Code de vérification">
                        <p class="verification-text">Scannez pour vérifier l'authenticité</p>
                    </div>
                </div>
            </div>
        </body>
        </html>
        HTML;
    }
    
    /**
     * Increment the download count for a certificate
     *
     * @param Certificate $certificate
     * @return Certificate
     */
    public function incrementDownloadCount(Certificate $certificate): Certificate
    {
        $certificate->download_count += 1;
        $certificate->save();
        
        return $certificate;
    }
}
