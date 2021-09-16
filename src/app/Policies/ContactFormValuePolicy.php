<?php

namespace VCComponent\Laravel\ConfigContact\Policies;

use VCComponent\Laravel\ConfigContact\Contracts\ContactFormValuePolicyInterface;

class ContactFormValuePolicy implements ContactFormValuePolicyInterface
{
    public function manage($user)
    {
        return $user->hasPermission('manage-contact-form-value');
    }
}