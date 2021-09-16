<?php

namespace VCComponent\Laravel\ConfigContact\Policies;

use VCComponent\Laravel\ConfigContact\Contracts\ContactFormValuePolicyInterface;

class ContactFormValuePolicy implements ContactFormValuePolicyInterface
{
    public function before($user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    public function manage($user)
    {
        return $user->hasPermission('manage-contact-form-value');
    }
}