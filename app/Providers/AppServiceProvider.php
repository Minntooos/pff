<?php

namespace App\Providers;

use App\Models\Resource;
use App\Policies\ResourcePolicy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->alias('PDF', Pdf::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        Blade::component('components.label', 'label');
        Blade::component('components.input', 'input');
        Blade::component('components.button', 'button');
        Blade::component('components.auth-validation-errors', 'auth-validation-errors');
        Blade::component('components.auth-session-status', 'auth-session-status');
        Blade::component('components.auth-card', 'auth-card');
        Blade::component('components.auth-logo', 'auth-logo');

        Gate::policy(Resource::class, ResourcePolicy::class);

        require_once app_path('Helpers/ResourceHelper.php');

    }
}
