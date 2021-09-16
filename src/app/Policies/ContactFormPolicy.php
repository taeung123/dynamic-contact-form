<?php

namespace VCComponent\Laravel\ConfigContact\Policies;

use VCComponent\Laravel\ConfigContact\Contracts\ContactFormPolicyInterface;

class ContactFormPolicy implements ContactFormPolicyInterface
{
    public function manage($user)
    {
        return $user->hasPermission('manage-contact-form');
    }
}