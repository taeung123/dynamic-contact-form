<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class ContactFormValidation extends AbstractValidator
{
    protected $rules = [
        'RULE_CREATE' => [
            'name' => ['required'],
            'status' => ['required'],
            'page' => ['required'],
            'position' => ['required'],
        ],
        'RULE_UPDATE' => [
            'name' => ['required'],
            'status' => ['required'],
            'page' => ['required'],
            'position' => ['required'],
        ],
    ];
}
