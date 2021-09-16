<?php

namespace VCComponent\Laravel\ConfigContact\Policies;

use VCComponent\Laravel\ConfigContact\Contracts\ContactFormPolicyInterface;

class ContactFormPolicy implements ContactFormPolicyInterface
{
    public function before($user, $ability)
    {
        if ($user->isAdministrator()) {
            return true;
        }
    }

    public function manage($user)
    {
        return $user->hasPermission('manage-contact-form');
    }
}