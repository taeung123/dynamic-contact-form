<?php

namespace VCComponent\Laravel\ConfigContact\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputItemRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputItemRepositoryEloquent;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepositoryEloquent;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputValidationRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputValidationRepositoryEloquent;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepositoryEloquent;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepositoryEloquent;

class ConfigContactServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(ContactFormRepository::class, ContactFormRepositoryEloquent::class);
        $this->app->bind(ContactFormValueRepository::class, ContactFormValueRepositoryEloquent::class);
        $this->app->bind(ContactFormInputRepository::class, ContactFormInputRepositoryEloquent::class);
        $this->app->bind(ContactFormInputItemRepository::class, ContactFormInputItemRepositoryEloquent::class);
        $this->app->bind(ContactFormInputValidationRepository::class, ContactFormInputValidationRepositoryEloquent::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
        $this->loadRoutesFrom(__DIR__ . "/../../routes/Api.php");
        $this->loadRoutesFrom(__DIR__ . "/../../routes/Web.php");
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", 'contact_form');
        $this->publishes([
            __DIR__ . "/../../resources/sass/_contact-form.scss" => base_path('/resources/sass/contact-form/_contact-form.scss'),
            __DIR__ . '/../../config/dynamic-contact-form.php'   => config_path('dynamic-contact-form.php'),
        ]);
    }
}
