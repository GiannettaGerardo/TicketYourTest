<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificaRefertoTampone extends Notification
{
    use Queueable;

    private $details;

    /**
     * Create a new notification instance.
     *
     * @param mixed $details
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Nuovo risultato tampone da TicketYourTest!')
            ->line('Se hai già un account, puoi visualizzare il tuo referto direttamente dal tuo storico personale:')
            ->action($this->details['actiontext'], $this->details['actionurl'])
            ->line('Oppure scarica ora il tuo referto!')
            ->attach(public_path(public_path($this->details['file_referto_path'])), [
                'as' => $this->details['file_referto_nome'],
                'mime' => 'text/pdf'
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
