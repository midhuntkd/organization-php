<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

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

        // Customize reset password URL to include organization slug and use custom route
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            $orgSlug = null;
            if ($notifiable instanceof User && $notifiable->organization) {
                $orgSlug = $notifiable->organization->slug;
            }

            if ($orgSlug) {
                // Build org-scoped reset URL with email as query param
                return url(route('org.password.reset', [
                    'organization' => $orgSlug,
                    'token' => $token,
                ], false)) . '?email=' . urlencode($notifiable->getEmailForPasswordReset());
            }

            // Fallback to default route
            return url(route('password.reset', [
                'token' => $token,
            ], false)) . '?email=' . urlencode($notifiable->getEmailForPasswordReset());
        });

        // Customize Reset Password email content and sender based on organization
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $orgName = config('app.name');
            $orgSlug = null;

            if ($notifiable instanceof \App\Models\User && $notifiable->organization) {
                $orgName = $notifiable->organization->name ?: $orgName;
                $orgSlug = $notifiable->organization->slug;
            }

            $url = $orgSlug
                ? route('org.password.reset', ['organization' => $orgSlug, 'token' => $token])
                : route('password.reset', ['token' => $token]);

            $url = url($url) . '?email=' . urlencode($notifiable->getEmailForPasswordReset());

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->from(config('mail.from.address'), $orgName)
                ->subject('Reset Password - ' . $orgName)
                ->line('You are receiving this email because we received a password reset request for your ' . $orgName . ' account.')
                ->action('Reset Password', $url)
                ->line('If you did not request a password reset, no further action is required.');
        });
    }
}
