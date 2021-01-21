<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface ContactFormInputRepository extends RepositoryInterface
{
    public function checkBySlug($contact_form_id, $slug, $slug_current = null);
}
