<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Registration;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    /**
     * Display a listing of the certificates for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        
        // For admin, show all certificates
        if ($user->isAdmin()) {
            $certificates = Certificate::with(['registration.user', 'registration.event'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            // For regular users, show only their certificates
            $certificates = Certificate::whereHas('registration', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['registration.event'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
        
        return view('certificates.index', compact('certificates'));
    }

    /**
     * Generate a certificate for a registration
     */
    public function generate(Registration $registration)
    {
        // Check if user is authorized to generate this certificate
        if (!Auth::user()->isAdmin() && $registration->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à générer ce certificat.');
        }
        
        try {
            $certificate = $this->certificateService->generateCertificate($registration);
            
            return redirect()->route('certificates.show', $certificate->id)
                ->with('success', 'Le certificat a été généré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de générer le certificat : ' . $e->getMessage());
        }
    }

    /**
     * Display the specified certificate
     */
    public function show(Certificate $certificate)
    {
        // Check if user is authorized to view this certificate
        if (!Auth::user()->isAdmin() && $certificate->registration->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce certificat.');
        }
        
        $certificate->load(['registration.user', 'registration.event']);
        
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Download the certificate PDF
     */
    public function download(Request $request, Certificate $certificate)
    {
        // Check if user is authorized to download this certificate
        if (!Auth::user()->isAdmin() && $certificate->registration->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à télécharger ce certificat.');
        }
        
        // Check if this is just for display (in iframe) or an actual download
        $isForDisplay = $request->has('display');
        
        // Only increment download count for actual downloads, not displays
        if (!$isForDisplay) {
            $this->certificateService->incrementDownloadCount($certificate);
        }
        
        // Get the file path
        $filePath = Storage::disk('public')->path($certificate->file_path);
        
        // For display in iframe, just return the file content
        if ($isForDisplay) {
            return response()->file($filePath);
        }
        
        // For download, return as attachment
        return response()->download(
            $filePath,
            'certificat_' . $certificate->registration->event->title . '.pdf'
        );
    }

    /**
     * Remove the specified certificate
     */
    public function destroy(Certificate $certificate)
    {
        // Check if user is authorized to delete this certificate
        if (!Auth::user()->isAdmin() && $certificate->registration->user_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer ce certificat.');
        }
        
        // Delete the file
        Storage::disk('public')->delete($certificate->file_path);
        
        // Delete the record
        $certificate->delete();
        
        return redirect()->route('certificates.index')
            ->with('success', 'Le certificat a été supprimé avec succès.');
    }
}
