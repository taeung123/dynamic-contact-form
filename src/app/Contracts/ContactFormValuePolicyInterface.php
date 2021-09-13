<?php

namespace VCComponent\Laravel\ConfigContact\Contracts;

interface ContactFormValuePolicyInterface
{
    public function ableToUse($user);
}