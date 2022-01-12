<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;

class ContactFormValueValidation extends AbstractValidator
{
    protected $rules = [
        "RULE_IDS"  => [
            'ids'  => ['array', 'required'],
            'ids*' => ['integer'],
        ],
    ];
}
