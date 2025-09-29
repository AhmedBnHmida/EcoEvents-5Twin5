<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Event;
use App\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistrationController extends Controller
{
    /**
     * Display listing of registrations (Admin)
     */
    public function index()
    {
        $registrations = Registration::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('registrations.index', compact('registrations'));
    }

    /**
     * Show the registration form for an event
     */
    public function create(Request $request)
    {
        // Validate event_id is provided
        if (!$request->has('event_id')) {
            return redirect()->route('events.public')
                ->with('error', 'Aucun événement sélectionné.');
        }

        $event = Event::findOrFail($request->event_id);

        // Check if user is authenticated
        if (!Auth::check()) {
            // Store intended URL in session to redirect back after login
            session(['url.intended' => route('registrations.create', ['event_id' => $event->id])]);
            return redirect()->route('sign-in')
                ->with('info', 'Veuillez vous connecter pour vous inscrire à cet événement.');
        }

        // Check if user is already registered
        $existingRegistration = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.public.show', $event->id)
                ->with('info', 'Vous êtes déjà inscrit à cet événement.');
        }

        // Check if event has capacity
        if ($event->registrations()->count() >= $event->capacity_max) {
            return redirect()->route('events.public.show', $event->id)
                ->with('error', 'Cet événement est complet.');
        }

        // All checks passed - show the registration form
        return view('registrations.create', compact('event'));
    }

    /**
     * Store a new registration
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('sign-in')
                ->with('error', 'Vous devez être connecté pour vous inscrire.');
        }

        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);

        // Check if already registered
        $existingRegistration = Registration::where('user_id', Auth::id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.public.show', $event->id)
                ->with('info', 'Vous êtes déjà inscrit à cet événement.');
        }

        // Check capacity
        if ($event->registrations()->count() >= $event->capacity_max) {
            return redirect()->route('events.public.show', $event->id)
                ->with('error', 'Cet événement est complet.');
        }

        // Generate unique ticket code
        $ticketCode = strtoupper(Str::random(8));

        // Generate QR code
        $qrCodePath = 'qrcodes/' . $ticketCode . '.png';
        $qrCodeFullPath = storage_path('app/public/' . $qrCodePath);
        
        // Create directory if it doesn't exist
        if (!file_exists(dirname($qrCodeFullPath))) {
            mkdir(dirname($qrCodeFullPath), 0755, true);
        }

        // Generate QR code with registration info
        $qrData = json_encode([
            'ticket_code' => $ticketCode,
            'event_id' => $event->id,
            'user_id' => Auth::id(),
        ]);

        // Create QR code (using simple text for now, you can install simplesoftwareio/simple-qrcode for better QR)
        file_put_contents($qrCodeFullPath, "QR Code: " . $qrData);

        // Create registration with PENDING status (admin must confirm)
        $registration = Registration::create([
            'user_id' => Auth::id(),
            'event_id' => $event->id,
            'ticket_code' => $ticketCode,
            'qr_code_path' => $qrCodePath,
            'status' => RegistrationStatus::PENDING->value,
            'registered_at' => now(),
        ]);

        return redirect()->route('registrations.show', $registration->id)
            ->with('success', 'Votre inscription a été enregistrée avec succès! Votre inscription est en attente de confirmation par l\'administrateur.');
    }

    /**
     * Display a registration (for participant to see their registration)
     */
    public function show(Registration $registration)
    {
        // Check if user can view this registration (owner or admin)
        if (!Auth::user()->isAdmin() && $registration->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $registration->load(['event', 'user']);
        
        return view('registrations.show', compact('registration'));
    }

    /**
     * Display user's registrations
     */
    public function myRegistrations()
    {
        if (!Auth::check()) {
            return redirect()->route('sign-in');
        }

        $registrations = Registration::with('event')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('registrations.my-registrations', compact('registrations'));
    }

    /**
     * Update registration status (Admin only)
     */
    public function updateStatus(Request $request, Registration $registration)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled,attended'
        ]);

        $oldStatus = $registration->status->value;
        $newStatus = $request->status;

        // Update registration status
        $registration->update([
            'status' => $newStatus
        ]);

        // CHANGE USER ROLE TO PARTICIPANT when admin CONFIRMS the registration
        if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
            $user = $registration->user;
            if ($user->role !== 'participant' && $user->role !== 'admin') {
                $user->role = 'participant';
                $user->save();
                
                return redirect()->back()
                    ->with('success', 'Inscription confirmée! L\'utilisateur ' . $user->name . ' est maintenant un participant.');
            }
        }

        return redirect()->back()
            ->with('success', 'Le statut de l\'inscription a été mis à jour.');
    }

    /**
     * Cancel a registration (User can cancel their own, Admin can cancel any)
     */
    public function destroy(Registration $registration)
    {
        // Check if user can delete this registration
        if (!Auth::user()->isAdmin() && $registration->user_id !== Auth::id()) {
            abort(403, 'Vous ne pouvez pas annuler cette inscription.');
        }

        $eventTitle = $registration->event->title;
        $registration->delete();

        return redirect()->back()
            ->with('success', 'Inscription à "' . $eventTitle . '" annulée avec succès.');
    }
}
