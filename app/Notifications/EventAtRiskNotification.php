<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;
use Carbon\Carbon;

class EventAtRiskNotification extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $event;
    protected $analysis;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event, string $analysis)
    {
        $this->event = $event;
        $this->analysis = $analysis;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $eventDate = Carbon::parse($this->event->start_date)->format('d/m/Y à H:i');
        
        return (new MailMessage)
            ->subject('⚠️ Alerte: Événement à risque - ' . $this->event->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons détecté que l\'événement suivant présente des risques basés sur les retours négatifs des participants:')
            ->line('**' . $this->event->title . '**')
            ->line('📅 Date : ' . $eventDate)
            ->line('📍 Lieu : ' . $this->event->location)
            ->line('🚨 **Analyse des problèmes:**')
            ->line($this->analysis)
            ->action('Voir les détails de l\'événement', route('events.public.show', $this->event->id))
            ->line('Merci de prendre les mesures nécessaires pour améliorer cet événement.')
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
            'analysis' => $this->analysis,
            'at_risk' => true,
            'notification_type' => 'event_risk',
        ];
    }
    
    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'analysis' => $this->analysis,
            'at_risk' => true,
            'notification_type' => 'event_risk',
        ];
    }
}
