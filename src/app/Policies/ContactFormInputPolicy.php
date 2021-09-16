<?php

namespace VCComponent\Laravel\ConfigContact\Policies;

use VCComponent\Laravel\ConfigContact\Contracts\ContactFormInputPolicyInterface;

class ContactFormInputPolicy implements ContactFormInputPolicyInterface
{
    public function manage($user)
    {
        return $user->hasPermission('manage-contact-form-input');
    }
}
