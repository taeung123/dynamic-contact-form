<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;
use VCComponent\Laravel\ConfigContact\Transformers\ContactFormValueTransformer;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ContactFormValueAdminController extends ApiController
{
    protected $contact_form_value_repository;
    protected $contact_form_value_transformer;
    protected $contact_form_value_entity;

    public function __construct(ContactFormValueRepository $contact_form_value_repository, ContactFormValueTransformer $contact_form_value_transformer)
    {
        $this->contact_form_value_repository  = $contact_form_value_repository;
        $this->contact_form_value_transformer = $contact_form_value_transformer;
        $this->contact_form_value_entity      = $contact_form_value_repository->getEntity();

        if (!empty(config('dynamic-contact-form.auth_middleware.admin'))) {
            $user = $this->getAuthenticatedUser();
            if (!$this->contact_form_value_entity->ableToUse($user)) {
                throw new PermissionDeniedException();
            }
            foreach (config('dynamic-contact-form.auth_middleware.admin') as $middleware) {
                $this->middleware($middleware['middleware'], ['except' => $middleware['except']]);
            }
        }
    }

    public function index(Request $request)
    {
        $perpage            = $request->has('per_page') ? $request->get('per_page') : 15;
        $contact_form_value = $this->contact_form_value_repository->orderBy('id', 'desc')->paginate($perpage);
        return $this->response->paginator($contact_form_value, new $this->contact_form_value_transformer);
    }

    public function show($id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);
        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }
        $contact_form_value = $this->contact_form_value_repository->find($id);
        return $this->response->item($contact_form_value, new $this->contact_form_value_transformer);
    }

    public function update(Request $request, $id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);
        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }
        $data               = $request->all();
        $data['payload']    = json_encode($request->payload);
        $contact_form_value = $this->contact_form_value_repository->update($data, $id);
        return $this->response->item($contact_form_value, $this->contact_form_value_transformer);
    }

    public function destroy($id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);
        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }
        $this->contact_form_value_repository->destroy($id);
        return $this->success();
    }

    public function getPayload(Request $request, $id)
    {
        $contact_form_value = $this->contact_form_value_repository->where('contact_form_id', $id)->exists();
        if (!$contact_form_value) {
            throw new Exception('Contact form does not exist');
        }
        $perpage            = $request->has('per_page') ? $request->get('per_page') : 15;
        $contact_form_value = $this->contact_form_value_repository->where('contact_form_id', $id)->paginate($perpage);
        return $this->response->paginator($contact_form_value, $this->contact_form_value_transformer);
    }

    public function changeStatus(Request $request, $id)
    {
        $contact_form_value = $this->contact_form_value_entity->find($id);
        if (!$contact_form_value) {
            throw new Exception('Contact form value does not exist');
        }
        $data               = $request->all();
        $contact_form_value = $this->contact_form_value_repository->update($data, $id);
        return $this->response->item($contact_form_value, $this->contact_form_value_transformer);
    }
}
