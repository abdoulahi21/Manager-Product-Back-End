<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommandeNotification extends Notification
{
    use Queueable;
    public Commande $commande;
    /**
     * Create a new notification instance.
     */
    public function __construct($commande)
    {
        $this->commande = $commande;
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
        return (new MailMessage)
            ->line('Bonjour,chère client(e) ' . $this->commande->client->nomcomplet.'votre commande a été passée avec succès.
            Vous recevrez un mail de confirmation dès que votre commande sera prête.Merci de votre confiance.')
            ->line('Numéro de commande : ' . $this->commande->numerocommande)
            ->line('Total : ' . $this->commande->total_price.' FCFA')
            ->action('Voir la commande', url('/commandes/' . $this->commande->id))
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
