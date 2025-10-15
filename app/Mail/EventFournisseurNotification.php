<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Ressource;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventFournisseurNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $ressource;

    public function __construct(Event $event, Ressource $ressource)
    {
        $this->event = $event;
        $this->ressource = $ressource;
    }

    public function build()
    {
        $htmlContent = "
        <div style='font-family: Arial, sans-serif; color: #333; line-height: 1.6; padding: 20px;'>
            <div style='max-width: 600px; margin: auto; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden;'>
                <div style='background-color: #4CAF50; color: white; padding: 20px; text-align: center;'>
                    <h2 style='margin: 0;'>Nouvelle Affectation de Ressource</h2>
                </div>
                <div style='padding: 20px;'>
                    <p>Bonjour <strong>{$this->ressource->fournisseur->name}</strong>,</p>
                    <p>Vous avez été affecté à la ressource suivante pour l’événement <strong>{$this->event->title}</strong> :</p>
                    <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Nom</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->ressource->nom}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Type</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->ressource->type}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Quantité</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->ressource->quantite}</td>
                        </tr>
                    </table>

                    <h3 style='color: #4CAF50;'>Détails de l’événement</h3>
                    <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Description</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->event->description}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Date début</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->event->start_date}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Date fin</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->event->end_date}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Lieu</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->event->location}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Capacité</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->event->capacity_max}</td>
                        </tr>
                        <tr>
                            <td style='border: 1px solid #ddd; padding: 8px; font-weight: bold;'>Prix</td>
                            <td style='border: 1px solid #ddd; padding: 8px;'>{$this->event->price} TND</td>
                        </tr>
                    </table>

                    <p style='background-color: #f9f9f9; padding: 10px; border-left: 4px solid #4CAF50;'>
                        Merci de préparer et livrer les ressources nécessaires avant le début de l’événement.
                    </p>

                    <p>Cordialement,<br><strong>" . config('app.name') . "</strong></p>
                </div>
                <div style='background-color: #f1f1f1; text-align: center; padding: 10px; font-size: 12px; color: #777;'>
                    &copy; " . date('Y') . " " . config('app.name') . ". Tous droits réservés.
                </div>
            </div>
        </div>
        ";

        return $this->subject("Nouvelle Affectation de Ressource - {$this->event->title}")
                    ->html($htmlContent);
    }
}
