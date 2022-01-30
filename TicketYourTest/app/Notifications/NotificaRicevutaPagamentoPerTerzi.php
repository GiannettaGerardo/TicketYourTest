<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class NotificaRicevutaPagamentoPerTerzi extends NotificaRicevutaPagamento implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param mixed $details
     * @return void
     */
    public function __construct($details)
    {
        parent::__construct($details);
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting($this->details['greeting'])
            ->line($this->details['nome_pagante']) // new
            ->line($this->details['email_pagante']) // new
            ->line($this->details['nome_laboratorio'])
            ->line($this->details['data_di_pagamento'])
            ->line($this->details['nome_tampone_effettuato'])
            ->line($this->details['importo_pagato'])
            ->line($this->details['id_transazione']);
    }
}
