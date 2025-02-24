<?php

namespace App\Providers;

use Mpdf\Tag\B;
use App\SmsServiceInterface;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use misterspelik\LaravelPdf\Facades\Pdf;
use App\Services\Notification\SmsService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsServiceInterface::class, SmsService::class);
        $this->app->bind('dompdf.pdf', function() {
            return new Pdf();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('permision', function ($permission) {
            return "<?php if (auth()->user()->role->permissions->contains('name', $permission)) : ?>";
        });

        Blade::directive('endpermision', function () {
            return "<?php endif; ?>";
        });
    }
}
