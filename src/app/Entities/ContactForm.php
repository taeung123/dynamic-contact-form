<?php

namespace VCComponent\Laravel\ConfigContact\Entites;

use Illuminate\Database\Eloquent\Model;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormValue;
use VCComponent\Laravel\ConfigContact\Traits\RenderFormMethods;

class ContactForm extends Model
{
    use RenderFormMethods;

    protected $fillable = [
        'id',
        'name',
        'status',
        'slug',
        'page',
        'position',
        'success_notification_content',
        'submit_button_content',
    ];

    public function contactFormInputs()
    {
        return $this->hasMany(ContactFormInput::class);
    }

    public function contactFormValues()
    {
        return $this->hasMany(ContactFormValue::class);
    }

    public function ableToUse($user)
    {
        return true;
    }

}
