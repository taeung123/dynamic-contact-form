<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class ContactFormInputValidationValidation extends AbstractValidator
{
    protected $rules = [
        'RULE_CREATE' => [
            'validation_name'       => ['required'],
            'validation_value'      => ['required'],
        ],
        'RULE_UPDATE' => [
            'validation_name'       => ['required'],
            'validation_value'      => ['required'],
        ],
    ];
}
