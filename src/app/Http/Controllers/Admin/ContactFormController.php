<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;
use VCComponent\Laravel\ConfigContact\Transformers\ContactFormTransformer;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormValidation;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ContactFormController extends ApiController
{
    use Helpers;

    protected $contact_form_transformer;
    protected $contact_form_repository;
    protected $contact_form_validation;
    protected $contact_form_entity;

    public function __construct(
        ContactFormRepository $contact_form_repository,
        ContactFormTransformer $contact_form_transformer,
        ContactFormValidation $contact_form_validation) {
        $this->contact_form_repository  = $contact_form_repository;
        $this->contact_form_transformer = $contact_form_transformer;
        $this->contact_form_validation  = $contact_form_validation;
        $this->contact_form_entity      = $contact_form_repository->getEntity();

        if (!empty(config('dynamic-contact-form.auth_middleware.admin'))) {
            $user = $this->getAuthenticatedUser();
            if (!$this->contact_form_entity->ableToUse($user)) {
                throw new PermissionDeniedException();
            }
            foreach (config('dynamic-contact-form.auth_middleware.admin') as $middleware) {
                $this->middleware($middleware['middleware'], ['except' => $middleware['except']]);
            }
        }
    }

    public function index(Request $request)
    {
        $perpage = $request->has('per_page') ? $request->get('per_page') : 15;
        if ($request->has('search')) {
            $contact_form = $this->contact_form_repository
                ->orderBy('id', 'desc')
                ->where("name", "like", "%" . $request->get('search') . "%")
                ->paginate($perpage);
        }
        $contact_form = $this->contact_form_repository->orderBy('id', 'desc')->paginate($perpage);
        return $this->response->paginator($contact_form, new $this->contact_form_transformer);
    }

    public function store(Request $request)
    {
        $this->contact_form_validation->isValid($request, 'RULE_CREATE');
        $data         = $request->all();
        $data['slug'] = $this->changeLabelToSlug($data['name']);
        $contact_form = $this->contact_form_repository->create($data);
        return $this->response->item($contact_form, new $this->contact_form_transformer);
    }

    public function update(Request $request, $id)
    {
        $this->contact_form_validation->isValid($request, 'RULE_UPDATE');

        $contact_form = $this->contact_form_entity->with('contactFormInputs')->find($id);
        if (!$contact_form) {
            throw new Exception('Contact form not found');
        }

        $data         = $request->all();
        $data['slug'] = $this->changeLabelToSlug($data['name']);
        $contact_form = $this->contact_form_repository->update($data, $id);
        return $this->response->item($contact_form, new $this->contact_form_transformer);
    }

    public function destroy($id)
    {
        $contact_form = $this->contact_form_entity->find($id);
        if (!$contact_form) {
            throw new Exception('Contact form not found');
        }
        $this->contact_form_repository->destroy($id);
        return $this->success();
    }

    public function show($id)
    {
        $contact_form = $this->contact_form_entity->has('contactFormInputs')->find($id);
        if (!$contact_form) {
            $contact_form = ['data' => array()];
            return response()->json($contact_form);
        }
        return $this->response->item($contact_form, new $this->contact_form_transformer);
    }

}
