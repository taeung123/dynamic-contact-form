<?php

namespace VCComponent\Laravel\ConfigContact\Policies;

use VCComponent\Laravel\ConfigContact\Contracts\ContactFormValuePolicyInterface;

class ContactFormValuePolicy implements ContactFormValuePolicyInterface
{
    public function ableToUse($user)
    {
        return true;
    }
}