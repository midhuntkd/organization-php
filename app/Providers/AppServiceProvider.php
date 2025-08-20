<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Customize the verification email to use your guest-safe route
        VerifyEmail::toMailUsing(function ($notifiable, $defaultUrl) {
            $url = URL::temporarySignedRoute(
                'custom.verification.verify',                             // your custom route name
                now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id'   => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return (new MailMessage)
                ->subject('Verify Your Email')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email', $url)
                ->line('If you did not create an account, no further action is required.');
        });
    }
}
