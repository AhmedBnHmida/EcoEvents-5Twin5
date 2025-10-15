<?php

namespace App\Notifications;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $registration;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $event = $this->registration->event;
        $qrCodeUrl = asset('storage/' . $this->registration->qr_code_path);

        return (new MailMessage)
            ->subject('Confirmation d\'inscription à ' . $event->title)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Votre inscription à l\'événement "' . $event->title . '" a été confirmée.')
            ->line('Date de l\'événement: ' . $event->start_date->format('d/m/Y H:i'))
            ->line('Lieu: ' . $event->location)
            ->line('Votre code de billet: ' . $this->registration->ticket_code)
            ->line('Veuillez présenter ce code ou le QR code ci-dessous à l\'entrée de l\'événement.')
            ->action('Voir mon inscription', url('/registrations/' . $this->registration->id))
            ->line('Merci de participer à notre événement écologique!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'registration_id' => $this->registration->id,
            'event_id' => $this->registration->event_id,
            'event_title' => $this->registration->event->title,
            'ticket_code' => $this->registration->ticket_code,
        ];
    }
}
