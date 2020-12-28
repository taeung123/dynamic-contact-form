<?php
namespace VCComponent\Laravel\ConfigContact\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInputItem;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputItemRepository;

class ContactFormInputItemRepositoryEloquent extends BaseRepository implements ContactFormInputItemRepository
{
    public function model()
    {
        return ContactFormInputItem::class;
    }
}
