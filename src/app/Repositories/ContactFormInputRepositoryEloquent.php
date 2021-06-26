<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepository;

class ContactFormInputRepositoryEloquent extends BaseRepository implements ContactFormInputRepository
{
    public function model()
    {
        if (isset(config('dynamic-contact-form.models')['contact_form_input'])) {
            return config('dynamic-contact-form.models.contact_form_input');
        } else {
            return ContactFormInput::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }


    public function checkBySlug($contact_form_id, $slug, $slug_current = null)
    {
        if ($slug_current === null) {
            return $this->getEntity()->whereHas('contactForm', function ($q) use ($contact_form_id) {
                $q->where('id', $contact_form_id);})
                ->where('slug', '=', $slug)
                ->exists();
        }
        return $this->getEntity()->whereHas('contactForm', function ($q) use ($contact_form_id) {
            $q->where('id', $contact_form_id);})
            ->where('slug', '=', $slug)
            ->where('slug', '!=', $slug_current)
            ->exists();
    }
}
