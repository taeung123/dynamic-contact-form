<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class ContactFormInputItemValidation extends AbstractValidator
{
    protected $rules = [
        'RULE_CREATE' => [
            'label'                 => ['required'],
            'value'                 => ['required'],
        ],
        'RULE_UPDATE' => [
            'label'                 => ['required'],
            'value'                 => ['required'],
        ],
    ];
}
