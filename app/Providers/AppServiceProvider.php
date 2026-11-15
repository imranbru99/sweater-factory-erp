<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Core\Contracts\SmsDriver;
use App\Modules\Core\Contracts\WhatsAppDriver;
use App\Modules\Core\Notifications\Drivers\LogSmsDriver;
use App\Modules\Core\Notifications\Drivers\LogWhatsAppDriver;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use ImranDev\UnicodePdf\UnicodePdfManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SmsDriver::class, LogSmsDriver::class);
        $this->app->bind(WhatsAppDriver::class, LogWhatsAppDriver::class);
    }

    public function boot(): void
    {
        Gate::before(function (User $user, string $ability): ?bool {
            if ($user->isSuperAdmin() || $user->hasPermission($ability)) {
                return true;
            }

            return null;
        });

        $this->registerPolicies();
        $this->registerBengaliFont();

        RateLimiter::for('login', function (Request $request): Limit {
            return Limit::perMinute(5)->by((string) $request->ip());
        });
    }

    private function registerPolicies(): void
    {
        foreach (['Core', 'MasterData'] as $module) {
            foreach (glob(app_path('Modules/'.$module.'/Policies/*Policy.php')) ?: [] as $file) {
                $policy = 'App\\Modules\\'.$module.'\\Policies\\'.basename($file, '.php');
                $label = substr(basename($file, '.php'), 0, -6);
                $model = $label === 'User'
                    ? User::class
                    : 'App\\Modules\\'.$module.'\\Models\\'.$label;

                if (class_exists($model) && class_exists($policy)) {
                    Gate::policy($model, $policy);
                }
            }
        }
    }

    private function registerBengaliFont(): void
    {
        $regular = (string) config('factory.pdf.bengali_regular');
        $bold = (string) config('factory.pdf.bengali_bold');

        if (! is_file($regular) || ! class_exists(UnicodePdfManager::class)) {
            return;
        }

        $this->app->make(UnicodePdfManager::class)->registerFont([
            'family' => 'Noto Sans Bengali',
            'regular' => $regular,
            'bold' => is_file($bold) ? $bold : $regular,
        ]);
    }
}
