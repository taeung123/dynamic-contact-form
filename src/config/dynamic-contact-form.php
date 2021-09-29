<?php
return [
    'models' => [
        'contact_form' => VCComponent\Laravel\ConfigContact\Entites\ContactForm::class,
        'contact_form_input' => VCComponent\Laravel\ConfigContact\Entites\ContactFormInput::class,
        'contact_form_input_item' => VCComponent\Laravel\ConfigContact\Entites\ContactFormInputItem::class,
        'contact_form_input_validation' => VCComponent\Laravel\ConfigContact\Entites\ContactFormInputValidation::class,
    ],
    'auth_middleware' => [
        'admin'    => [
            [
                'middleware' => '',
                'except'     => [],
            ],
        ],
        'frontend' => [
            [
                'middleware' => '',
                'except'     => [],
            ],
        ],
    ],
];
