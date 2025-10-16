<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $registration;
    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
        $this->event = $registration->event;
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
        $startDate = Carbon::parse($this->event->start_date);
        $formattedDate = $startDate->format('d/m/Y à H:i');
        $remainingHours = now()->diffInHours($startDate, false);
        
        return (new MailMessage)
            ->subject('Rappel : Votre événement "' . $this->event->title . '" a lieu demain')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous rappelons que vous êtes inscrit(e) à l\'événement suivant qui aura lieu demain :')
            ->line('**' . $this->event->title . '**')
            ->line('📅 Date : ' . $formattedDate)
            ->line('📍 Lieu : ' . $this->event->location)
            ->line('🎫 Votre code de ticket : **' . $this->registration->ticket_code . '**')
            ->action('Voir les détails de l\'événement', route('events.public.show', $this->event->id))
            ->line('N\'oubliez pas de présenter votre QR code à l\'entrée de l\'événement.')
            ->line('À très bientôt !')
            ->salutation('L\'équipe EcoEvents');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'registration_id' => $this->registration->id,
            'start_date' => $this->event->start_date,
        ];
    }
}
