<?php

namespace App\Notifications;

use App\Ballot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class BallotSent extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Ballot
     */
    private $ballot;
    private $resending;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ballot $ballot, $resending = false)
    {
        $this->ballot = $ballot;
        $this->resending = $resending;
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
            ->subject($this->ballot->election->name)
            ->cc($this->resending ? $this->ballot->formatCCRecipients() : [])
            ->greeting('Tavas vēlēšanu zīmes parole')
            ->line(Crypt::decryptString($this->ballot->password))
            ->action('Balsot', action('BallotController@show', $this->ballot))
            ->line('Jautājumu vai neskaidrību gadījumā raksti uz balsosana@lusp.lv')
            ->replyTo('balsosana@lusp.lv');
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
