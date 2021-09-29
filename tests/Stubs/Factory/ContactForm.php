<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormValue;

$factory->define(ContactForm::class, function (Faker $faker) {
    $name = $faker->words(rand(4, 7), true);
    $slug = str_replace("-", "_", Str::slug($name));
    return [
        'name' => $name,
        'slug' => $slug,
        'status' => 1,
        'page' => 'contact',
        'position' => 'position-1',
        'submit_button_content' => $faker->words(rand(4, 7), true),
        'success_notification_content' => $faker->words(rand(4, 7), true),
    ];
});

$factory->define(ContactFormInput::class, function (Faker $faker) {
    $label = $faker->words(rand(4, 7), true);
    $slug = str_replace("-", "_", Str::slug($label));
    return [
        'contact_form_id' => 1,
        'type_input' => 'text',
        'label' => $label,
        'slug' => $slug,
        'order' => 0,
        'placeholder' => $faker->words(rand(4, 7), true),
    ];
});
$factory->define(ContactFormValue::class, function (Faker $faker) {
    return [
        'contact_form_id' => 1,
        'payload' => $faker->words(rand(10, 15), true),
        'status' => 1,
    ];
});
