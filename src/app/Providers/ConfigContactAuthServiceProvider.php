<?php

namespace VCComponent\Laravel\ConfigContact\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class ConfigContactAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('manage-contact-form', 'VCComponent\Laravel\User\Contracts\ContactFormPolicyInterface@ableToUse');
        Gate::define('manage-contact-form-intput', 'VCComponent\Laravel\User\Contracts\ContactFormInputPolicyInterface@ableToUse');
        Gate::define('manage-contact-form-value', 'VCComponent\Laravel\User\Contracts\ContactFormValuePolicyInterface@ableToUse');
        //
    }
}
