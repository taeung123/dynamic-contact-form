<?php

namespace VCComponent\Laravel\ConfigContact\Contracts;

interface ContactFormPolicyInterface
{
    public function ableToUse($user);
}