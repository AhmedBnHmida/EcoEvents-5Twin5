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

        // Generate QR code for certificate verification
        $verificationCode = $this->generateVerificationQrCode($registration);
        
        // Generate PDF
        $pdf = $this->generatePdf($registration, $verificationCode);
        
        // Save PDF to storage
        $filePath = 'certificates/' . $registration->ticket_code . '_certificate.pdf';
        Storage::disk('public')->put($filePath, $pdf->output());
        
        // Create certificate record
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
        
        $qrCode = QrCode::format('png')
            ->size(200)
            ->errorCorrection('H')
            ->generate(json_encode($verificationData));
            
        return 'data:image/png;base64,' . base64_encode($qrCode);
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
