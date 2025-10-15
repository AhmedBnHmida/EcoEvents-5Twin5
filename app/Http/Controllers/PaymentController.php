<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use App\RegistrationStatus;
use App\Notifications\RegistrationConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    /**
     * Initialize the payment process for a registration
     */
    public function checkout(Registration $registration)
    {
        // Check if user is authorized to pay for this registration
        if ($registration->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Vous n\'êtes pas autorisé à effectuer ce paiement.');
        }

        // Check if registration is already confirmed
        if ($registration->status->value === RegistrationStatus::CONFIRMED->value) {
            return redirect()->route('registrations.show', $registration->id)
                ->with('info', 'Cette inscription est déjà confirmée.');
        }

        // Check if registration is canceled
        if ($registration->status->value === RegistrationStatus::CANCELED->value) {
            return redirect()->route('registrations.show', $registration->id)
                ->with('error', 'Cette inscription a été annulée et ne peut pas être payée.');
        }

        // Get event details
        $event = $registration->event;
        
        // If event is free, confirm registration and redirect
        if ($event->price <= 0) {
            $registration->update(['status' => RegistrationStatus::CONFIRMED->value]);
            $user = $registration->user;
            $user->notify(new RegistrationConfirmation($registration));
            
            // Change user role to participant
            if ($user->role !== 'participant' && $user->role !== 'admin') {
                $user->role = 'participant';
                $user->save();
            }
            
            return redirect()->route('registrations.show', $registration->id)
                ->with('success', 'Votre inscription a été confirmée avec succès! Vous recevrez un email de confirmation.');
        }

        try {
            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => $event->title,
                                'description' => 'Inscription à l\'événement: ' . $event->title,
                            ],
                            'unit_amount' => $event->price * 100, // Stripe uses cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['registration' => $registration->id, 'session_id' => '{CHECKOUT_SESSION_ID}']),
                'cancel_url' => route('payment.cancel', ['registration' => $registration->id]),
                'client_reference_id' => $registration->id,
                'customer_email' => Auth::user()->email,
                'metadata' => [
                    'registration_id' => $registration->id,
                    'event_id' => $event->id,
                    'user_id' => Auth::id(),
                ],
            ]);

            return view('payments.checkout', [
                'registration' => $registration,
                'event' => $event,
                'checkout_session_id' => $session->id,
                'stripe_key' => config('services.stripe.key'),
            ]);
        } catch (ApiErrorException $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'initialisation du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request, Registration $registration)
    {
        // Log the request for debugging
        \Log::info('Payment success callback received', [
            'registration_id' => $registration->id,
            'session_id' => $request->session_id,
            'request_data' => $request->all()
        ]);
        
        // Force update the registration status to confirmed
        $registration->status = RegistrationStatus::CONFIRMED->value;
        $registration->save();
        
        // Get user and update role
        $user = $registration->user;
        if ($user->role !== 'participant' && $user->role !== 'admin') {
            $user->role = 'participant';
            $user->save();
        }
        
        // Send confirmation email
        try {
            $user->notify(new RegistrationConfirmation($registration));
            \Log::info('Confirmation email sent to user', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email', ['error' => $e->getMessage()]);
        }
        
        // Verify session_id is present
        if (!$request->has('session_id')) {
            \Log::warning('No session_id provided in success callback');
            return redirect()->route('registrations.show', $registration->id)
                ->with('success', 'Votre inscription a été confirmée avec succès!');
        }

        try {
            // Set Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Retrieve the session to verify payment status
            $session = StripeSession::retrieve($request->session_id);
            \Log::info('Stripe session retrieved', ['payment_status' => $session->payment_status]);

            // Verify payment was successful
            if ($session->payment_status !== 'paid') {
                \Log::warning('Payment not marked as paid', ['payment_status' => $session->payment_status]);
            }

            // Verify this session is for this registration
            if ($session->client_reference_id != $registration->id) {
                \Log::warning('Session client_reference_id does not match registration ID', [
                    'client_reference_id' => $session->client_reference_id,
                    'registration_id' => $registration->id
                ]);
            }

            return redirect()->route('registrations.show', $registration->id)
                ->with('success', 'Paiement réussi! Votre inscription est maintenant confirmée.');
        } catch (ApiErrorException $e) {
            \Log::error('Stripe API error', ['error' => $e->getMessage()]);
            return redirect()->route('registrations.show', $registration->id)
                ->with('success', 'Votre inscription a été confirmée avec succès!');
        }
    }

    /**
     * Handle canceled payment
     */
    public function cancel(Registration $registration)
    {
        return redirect()->route('registrations.show', $registration->id)
            ->with('info', 'Paiement annulé. Vous pouvez réessayer plus tard.');
    }

    /**
     * Handle Stripe webhook events
     */
    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook_secret');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $registration_id = $session->metadata->registration_id;
                
                if ($registration_id) {
                    $registration = Registration::find($registration_id);
                    if ($registration) {
                        // Update registration status
                        $registration->update(['status' => RegistrationStatus::CONFIRMED->value]);
                        
                        // Get user and send confirmation email
                        $user = $registration->user;
                        $user->notify(new RegistrationConfirmation($registration));
                        
                        // Change user role to participant
                        if ($user->role !== 'participant' && $user->role !== 'admin') {
                            $user->role = 'participant';
                            $user->save();
                        }
                    }
                }
                break;
            default:
                // Unexpected event type
                break;
        }

        return response()->json(['status' => 'success']);
    }
}
