<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services in the service container.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap application-wide defaults.
     */
    public function boot(): void
    {
        $this->configureDefaults();
    }

    /**
     * Configure secure and predictable application defaults.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        /*
         * Prevent destructive database console commands from running in the
         * production environment.
         */
        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        /*
         * Apply stronger password requirements in production while allowing
         * Laravel's normal defaults during local development and testing.
         */
        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
