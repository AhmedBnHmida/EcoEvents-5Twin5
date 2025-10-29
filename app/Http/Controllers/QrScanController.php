<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QrScanController extends Controller
{
    /**
     * Affiche la page de scan de QR code
     */
    public function showScanPage()
    {
        // Vérifier que l'utilisateur est un organisateur ou un admin
        if (!Auth::user()->isAdmin() && !Auth::user()->isOrganisateur()) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('qrscan.scan');
    }

    /**
     * Traite les données du QR code scanné
     */
    public function processScan(Request $request)
    {
        // Vérifier que l'utilisateur est un organisateur ou un admin
        if (!Auth::user()->isAdmin() && !Auth::user()->isOrganisateur()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $request->validate([
            'qr_data' => 'required|string',
        ]);

        try {
            // Décoder les données du QR code
            $qrData = json_decode($request->qr_data, true);
            
            if (!$qrData || !isset($qrData['ticket_code'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR code invalide ou mal formaté'
                ]);
            }

            // Rechercher l'inscription correspondante
            $registration = Registration::where('ticket_code', $qrData['ticket_code'])->first();
            
            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucune inscription trouvée avec ce code'
                ]);
            }

            // Charger les relations pour afficher les informations
            $registration->load(['user', 'event']);
            
            // Vérifier si l'inscription est déjà marquée comme "attended"
            if ($registration->status->value === 'attended') {
                return response()->json([
                    'success' => true,
                    'registration' => $registration,
                    'message' => 'Ce participant a déjà été enregistré comme présent',
                    'alreadyAttended' => true
                ]);
            }

            // Retourner les informations de l'inscription pour confirmation
            return response()->json([
                'success' => true,
                'registration' => $registration,
                'message' => 'Inscription trouvée. Confirmez la présence du participant.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors du traitement du QR code: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement du QR code'
            ]);
        }
    }

    /**
     * Marque un participant comme présent
     */
    public function markAsAttended(Request $request)
    {
        // Vérifier que l'utilisateur est un organisateur ou un admin
        if (!Auth::user()->isAdmin() && !Auth::user()->isOrganisateur()) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }

        $request->validate([
            'registration_id' => 'required|exists:registrations,id',
        ]);

        try {
            $registration = Registration::findOrFail($request->registration_id);
            
            // Mettre à jour le statut à "attended"
            $registration->status = RegistrationStatus::ATTENDED;
            $registration->attended_at = now();
            $registration->save();

            return response()->json([
                'success' => true,
                'message' => 'Participant marqué comme présent avec succès',
                'registration' => $registration->load(['user', 'event'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du statut'
            ]);
        }
    }
}