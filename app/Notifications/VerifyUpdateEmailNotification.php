<?php
namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyUpdateEmailNotification extends Notification
{
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('メールアドレス変更リンク通知')
            ->line('メールアドレスを変更するには下記のボタンをクリックしてください。')
            ->action(
                'メールアドレス変更',
                $this->verificationUrl($notifiable)
            )
            ->line('もしこのメールに心当たりがない場合、このメールを破棄いただきますようお願いします。');
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'user.account.verify',
            Carbon::now()->addMinutes(60),
            ['email' => $this->email]
        );
    }
}
