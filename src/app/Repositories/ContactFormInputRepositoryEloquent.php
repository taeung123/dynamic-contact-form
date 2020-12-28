<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepository;
class ContactFormInputRepositoryEloquent extends BaseRepository implements ContactFormInputRepository
{
    public function model()
    {
        return ContactFormInput::class;
    }
}
