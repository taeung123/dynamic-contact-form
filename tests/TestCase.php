<?php

namespace VCComponent\Laravel\ConfigContact\Test;

use Dingo\Api\Provider\LaravelServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use VCComponent\Laravel\ConfigContact\Providers\ConfigContactServiceProvider;

class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return HaiCS\Laravel\Generator\Providers\GeneratorServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class,
            ConfigContactServiceProvider::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withFactories(__DIR__ . '/../tests/Stubs/Factory');
        // $this->loadMigrationsFrom(__DIR__ . '/../src/database/migrations');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:TEQ1o2POo+3dUuWXamjwGSBx/fsso+viCCg9iFaXNUA=');
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('dynamic-contact-form.namespace', 'dynamic-contact-form-management');
        $app['config']->set('dynamic-contact-form.models', [
            'dynamic-contact-form' => \VCComponent\Laravel\ConfigContact\Entites\ContactForm::class,

        ]);
        $app['config']->set('dynamic-contact-form.transformers', [
            'dynamic-contact-form' => \VCComponent\Laravel\ConfigContact\Transformers\ContactFormTransformer::class,
        ]);

        $app['config']->set('dynamic-contact-form', [
            'page' => [
                'contact' => [
                    'label' => 'Contact',
                    'position' => [
                        'position-1' => 'On the left',
                        'position-2' => 'On the right',
                    ],
                ],

                'service' => [
                    'label' => 'servicePage',
                    'position' => [
                        'position-1' => 'Default',
                    ],
                ],
            ],
        ]);

    }
    public function assertValidation($response, $field, $error_message)
    {
        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'The given data was invalid.',
            "errors" => [
                $field => [
                    $error_message,
                ],
            ],
        ]);
    }
    public function assertAlreadyExist($response)
    {
        $response->assertJson([
            'data' =>
            [
                "status" => "error",
                "notifcation" => "Contact form already exists",
            ],
        ]);
    }
    public function assertInputAlreadyExist($response)
    {
        $response->assertJson([
            'data' =>
            [
                "status" => "error",
                "notifcation" => "Input label already exists",
                'contact_form_id' => 1,
            ],
        ]);
    }
}
