<?php

declare(strict_types=1);

namespace Contexts\Account\Application\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class EmailConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $name)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(sprintf('Hi %s', $this->name))
            ->line('Your email address was confirmed successfully!')
            ->line('Welcome!');
    }
}
