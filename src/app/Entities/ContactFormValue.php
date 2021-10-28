<?php

namespace VCComponent\Laravel\ConfigContact\Entites;

use Illuminate\Database\Eloquent\Model;

class ContactFormValue extends Model
{
    protected $fillable = [
        'id',
        'contact_form_id',
        'payload',
        'status',
        'payload_slug'
    ];

    public function ableToUse($user)
    {
        return true;
    }
}
