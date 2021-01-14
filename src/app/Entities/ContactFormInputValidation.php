<?php

namespace VCComponent\Laravel\ConfigContact\Entites;

use Illuminate\Database\Eloquent\Model;

class ContactFormInputValidation extends Model
{
    protected $fillable = [
        'id',
        'contact_form_input_id',
        'validation_name',
        'validation_value',
    ];
}
