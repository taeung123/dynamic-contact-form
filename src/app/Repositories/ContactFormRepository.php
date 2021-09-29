<?php

namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;


interface ContactFormRepository extends RepositoryInterface
{
    public function checkBySlug($slug, $slug_current = null);
}
