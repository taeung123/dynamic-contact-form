<?php

namespace VCComponent\Laravel\ConfigContact\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use VCComponent\Laravel\ConfigContact\Contracts\ContactFormInputPolicyInterface;
use VCComponent\Laravel\ConfigContact\Contracts\ContactFormPolicyInterface;
use VCComponent\Laravel\ConfigContact\Contracts\ContactFormValuePolicyInterface;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormValue;

class ConfigContactAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ContactForm::class => ContactFormPolicyInterface::class,
        ContactFormInput::class => ContactFormInputPolicyInterface::class,
        ContactFormValue::class => ContactFormValuePolicyInterface::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //
    }
}
