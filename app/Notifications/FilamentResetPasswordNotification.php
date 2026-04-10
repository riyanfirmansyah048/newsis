<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;

class FilamentResetPasswordNotification extends BaseResetPassword
{
    public string $url;

    protected function resetUrl($notifiable): string
    {
        return $this->url;
    }
}
