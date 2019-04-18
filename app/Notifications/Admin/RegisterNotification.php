<?php
namespace App\Notifications\Admin;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterNotification extends Notification
{
    protected $password;

    public function getPassword()
    {
        return $this->password;
    }

    public function __construct($password)
    {
        $this->password = $password;
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
            ->subject('【' . env('APP_NAME') . '】管理者ユーザー登録のお知らせ')
            ->line('管理者ユーザーとして登録されました。')
            ->line('下記のアドレスからログインし、パスワードを変更してください。')
            ->line('パスワード: ' . $this->password)
            ->action('ログイン', url('/admin/login'))
            ->line('もしこのメールに心当たりがない場合、このメールを破棄いただきますようお願いします。');
    }
}
