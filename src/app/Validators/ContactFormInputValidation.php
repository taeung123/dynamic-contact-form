<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class ContactFormInputValidation extends AbstractValidator
{
    protected $rules = [
        'RULE_CREATE' => [
            'contact_form_id' => ['required'],
            'type_input'      => ['required'],
            'label'           => ['required'],
        ],
        'RULE_UPDATE' => [
            'contact_form_id' => ['required'],
            'type_input'      => ['required'],
            'label'           => ['required'],
        ],
    ];
}
