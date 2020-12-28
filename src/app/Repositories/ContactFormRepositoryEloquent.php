<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;

class ContactFormRepositoryEloquent extends BaseRepository implements ContactFormRepository
{
    public function model()
    {
        return ContactForm::class;
    }
    public function getEntity()
    {
        return $this->model;
    }
}
