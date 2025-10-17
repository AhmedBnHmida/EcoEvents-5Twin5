<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventThankYouNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $registration;
    protected $event;
    protected $hasCertificate;

    /**
     * Create a new notification instance.
     */
    public function __construct(Registration $registration, bool $hasCertificate = false)
    {
        $this->registration = $registration;
        $this->event = $registration->event;
        $this->hasCertificate = $hasCertificate;
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
        $mail = (new MailMessage)
            ->subject('Merci pour votre participation à "' . $this->event->title . '"')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous remercions d\'avoir participé à notre événement :')
            ->line('**' . $this->event->title . '**')
            ->line('Votre présence a contribué au succès de cet événement, et nous espérons que vous avez apprécié cette expérience.')
            ->line('Nous serions ravis de connaître votre avis sur cet événement.');
        
        // Si un certificat a été généré
        if ($this->hasCertificate) {
            $mail->line('**Votre certificat de participation est maintenant disponible !**')
                ->action('Télécharger mon certificat', route('certificates.index'))
                ->line('Ce certificat atteste de votre participation à l\'événement et peut être téléchargé depuis votre espace personnel.');
        }
        
        // Ajouter le lien pour donner un feedback
        $mail->line('Nous vous invitons à partager votre expérience en laissant un commentaire :')
            ->action('Donner mon avis', route('feedback.create', ['event_id' => $this->event->id]))
            ->line('Nous espérons vous revoir très bientôt lors de nos prochains événements.')
            ->salutation('L\'équipe EcoEvents');
        
        return $mail;
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
            'has_certificate' => $this->hasCertificate,
        ];
    }
}
