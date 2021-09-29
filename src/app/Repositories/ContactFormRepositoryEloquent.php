<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;

class ContactFormRepositoryEloquent extends BaseRepository implements ContactFormRepository
{
    public function model()
    {
        if (isset(config('dynamic-contact-form.models')['contact_form'])) {
            return config('dynamic-contact-form.models.contact_form');
        } else {
            return ContactForm::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }

    public function checkBySlug($slug, $slug_current = null)
    {
        if ($slug_current === null) {
            return $this->getEntity()->where('slug', '=', $slug)->exists();
        }
        return $this->getEntity()->where('slug', '=', $slug)->where('slug', '!=', $slug_current)->exists();
    }
}
