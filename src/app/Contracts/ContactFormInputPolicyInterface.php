<?php

namespace VCComponent\Laravel\ConfigContact\Contracts;

interface ContactFormInputPolicyInterface
{
    public function ableToUse($user);
}