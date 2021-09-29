<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInputItem;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputItemRepository;

class ContactFormInputItemRepositoryEloquent extends BaseRepository implements ContactFormInputItemRepository
{
    public function model()
    {
        if (isset(config('dynamic-contact-form.models')['contact_form_input_item'])) {
            return config('dynamic-contact-form.models.contact_form_input_item');
        } else {
            return ContactFormInputItem::class;
        }
    }

    public function getEnity()
    {
        return $this->model;
    }
}
