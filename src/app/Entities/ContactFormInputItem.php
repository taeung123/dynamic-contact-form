<?php

namespace VCComponent\Laravel\ConfigContact\Entites;

use Illuminate\Database\Eloquent\Model;

class ContactFormInputItem extends Model
{
    protected $fillable = [
        'id',
        'contact_form_input_id',
        'key',
        'order',
        'slug',
        'label',
        'value',
    ];
}
