<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInputValidation;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputValidationRepository;

class ContactFormInputValidationRepositoryEloquent extends BaseRepository implements ContactFormInputValidationRepository
{
    public function model()
    {
        if (isset(config('dynamic-contact-form.models')['contact_form_input_validation'])) {
            return config('dynamic-contact-form.models.contact_form_input_validation');
        } else {
            return ContactFormInputValidation::class;
        }
    }

    public function getEnity()
    {
        return $this->model;
    }
}
