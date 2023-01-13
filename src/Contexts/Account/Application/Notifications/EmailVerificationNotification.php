<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $name, private readonly string $code)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(sprintf('Welcome in %s', config('app.name')))
            ->line(sprintf('Thanks for registration %s!', $this->name))
            ->line(sprintf('Your activation code: %s.', $this->code));
    }
}
