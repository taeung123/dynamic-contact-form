<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormValue;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;

class ContactFormValueRepositoryEloquent extends BaseRepository implements ContactFormValueRepository
{
    public function model()
    {
        return ContactFormValue::class;
    }
}
