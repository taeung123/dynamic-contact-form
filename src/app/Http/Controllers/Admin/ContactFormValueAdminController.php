<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;
use VCComponent\Laravel\ConfigContact\Transformers\ContactFormValueTransformer;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class ContactFormValueAdminController extends ApiController
{
    protected $contact_form_value_repository;
    protected $contact_form_value_transformer;

    public function __construct(ContactFormValueRepository $contact_form_value_repository, ContactFormValueTransformer $contact_form_value_transformer)
    {
        $this->contact_form_value_repository = $contact_form_value_repository;
        $this->contact_form_value_transformer = $contact_form_value_transformer;
    }
    public function index(Request $request)
    {
        $perpage      = $request->has('per_page') ? $request->get('per_page') : 15;
        $contact_form_value = $this->contact_form_value_repository->orderBy('id', 'desc')->paginate($perpage);
        return $this->response->paginator($contact_form_value, new $this->contact_form_value_transformer);
    }
}
