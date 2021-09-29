<?php

namespace VCComponent\Laravel\ConfigContact\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\ConfigContact\Contracts\ContactFormInputPolicyInterface;
use VCComponent\Laravel\ConfigContact\Contracts\ContactFormPolicyInterface;
use VCComponent\Laravel\ConfigContact\Contracts\ContactFormValuePolicyInterface;
use VCComponent\Laravel\ConfigContact\Policies\ContactFormInputPolicy;
use VCComponent\Laravel\ConfigContact\Policies\ContactFormPolicy;
use VCComponent\Laravel\ConfigContact\Policies\ContactFormValuePolicy;
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
        $this->app->bind(ContactFormPolicyInterface::class, ContactFormPolicy::class);
        $this->app->bind(ContactFormInputPolicyInterface::class, ContactFormInputPolicy::class);
        $this->app->bind(ContactFormValuePolicyInterface::class, ContactFormValuePolicy::class);

        $this->app->register(ConfigContactAuthServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
        $this->loadRoutesFrom(__DIR__ . "/../../routes/Api.php");
        $this->loadRoutesFrom(__DIR__ . "/../../routes/Web.php");
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", 'contact_form');
        $this->publishes([
            __DIR__ . '/../../config/dynamic-contact-form.php' => config_path('dynamic-contact-form.php'),
        ]);
    }
}
