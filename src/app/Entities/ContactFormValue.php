<?php

namespace VCComponent\Laravel\ConfigContact\Entites;

use Illuminate\Database\Eloquent\Model;

class ContactFormValue extends Model
{
    protected $fillable = [
        'id',
        'contact_form_id',
        'payload'
    ];

    public function ableToUse($user)
    {
        return true;
    }
}
