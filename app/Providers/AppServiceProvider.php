<?php

namespace App\Providers;

use App\Services\Tts\LemonfoxTts;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LemonfoxTts::class, fn () => new LemonfoxTts(
            apiKey: (string) config('services.lemonfox.key'),
            endpoint: (string) config('services.lemonfox.endpoint'),
            voice: (string) config('services.lemonfox.voice'),
            language: (string) config('services.lemonfox.language'),
            timeout: (int) config('services.lemonfox.timeout'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
