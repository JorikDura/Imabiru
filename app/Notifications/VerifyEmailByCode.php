<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Random\RandomException;

class VerifyEmailByCode extends Notification implements ShouldQueue
{
    use Queueable;

    private const int MIN = 1000;
    private const int MAX = 9999;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * @throws RandomException
     */
    public function toMail($notifiable): MailMessage
    {
        $code = $this->verificationCode($notifiable);

        return $this->buildMailMessage($code);
    }

    protected function buildMailMessage($code): MailMessage
    {
        return (new MailMessage())
            ->subject('Verify Email Address')
            ->line('Your verification code:')
            ->line($code)
            ->line('If you did not create an account, no further action is required.');
    }

    /**
     * @throws RandomException
     */
    protected function verificationCode($notifiable): int
    {
        /** @var User $notifiable */

        $code = random_int(self::MIN, self::MAX);

        Cache::remember(
            key: "email-{$notifiable->getKey()}",
            ttl: Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            callback: fn () => $code
        );

        return $code;
    }
}
