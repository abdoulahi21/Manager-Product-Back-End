<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Commande;

class VendeurCommandeNotification extends Notification
{
    use Queueable;

    public Commande $commande;

    public function __construct($commande)
    {
        $this->commande = $commande;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('Une nouvelle commande a été passée.')
            ->line('Numéro de commande : ' . $this->commande->numerocommande)
            ->line('Total : ' . $this->commande->total_price)
            ->action('Voir la commande', url('/commandes/' . $this->commande->id))
            ->line('Merci d\'utiliser notre application !');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
