<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Traits\CheckRequestMethods;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;
use VCComponent\Laravel\ConfigContact\Transformers\ContactFormTransformer;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormValidation;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ContactFormController extends ApiController
{
    use Helpers, CheckRequestMethods;

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
        $query = $this->contact_form_repository;
        $query = $this->checkStatusRequest($request, $query);
        $query = $this->checkSearchRequest($request, "name", $query);
        $query = $query->orderBy('id', 'desc');
        $query = $this->checkPerPageRequest($request, $query);

        $contact_form = $query;

        return $this->response->paginator($contact_form, $this->contact_form_transformer);
    }

    public function store(Request $request)
    {
        $this->contact_form_validation->isValid($request, 'RULE_CREATE');
        $data              = $request->all();
        $data['slug']      = $this->changeLabelToSlug($data['name']);
        $check_name_exists = $this->contact_form_repository
            ->where('slug', '=', $data['slug'])
            ->exists();
        if ($check_name_exists) {
            $data_response = [
                'data' => ['status' => 'error', 'notifcation' => 'Contact form already exists'],
            ];
            return response()->json($data_response);
        }

        $contact_form = $this->contact_form_repository->create($data);

        return $this->response->item($contact_form, new $this->contact_form_transformer);
    }

    public function update(Request $request, $id)
    {
        $this->contact_form_validation->isValid($request, 'RULE_UPDATE');

        $contact_form = $this->contact_form_entity->with('contactFormInputs')->find($id);
        if (!$contact_form) {
            throw new Exception('Contact form does not exist');
        }

        $name_current      = $this->contact_form_repository->find($id)->name;
        $data              = $request->all();
        $name              = $this->removeSpaceOfString($data['name']);
        $check_name_exists = $this->contact_form_repository
            ->where('name', '=', $name)
            ->where('name', '!=', $name_current)
            ->exists();
        if ($check_name_exists) {
            throw new Exception('Contact form already exist');
        }

        $data['name'] = $name;
        $data['slug'] = $this->changeLabelToSlug($name);
        $contact_form = $this->contact_form_repository->update($data, $id);

        return $this->response->item($contact_form, new $this->contact_form_transformer);
    }

    public function destroy($id)
    {
        $contact_form = $this->contact_form_entity->find($id);

        if (!$contact_form) {
            throw new Exception('Contact form does not exist');
        }

        $this->contact_form_repository->destroy($id);

        return $this->success();
    }

    public function show($id)
    {
        $contact_form = $this->contact_form_entity->with(['contactFormInputs' => function ($q) {
            $q->orderBy('id', 'desc');
        }])->find($id);

        if (!$contact_form) {
            $contact_form = ['data' => array()];
            return response()->json($contact_form);
        }

        return $this->response->item($contact_form, new $this->contact_form_transformer);
    }

    function list() {
        $contact_form = $this->contact_form_repository->orderBy('id', 'desc')->get();
        return $this->response->collection($contact_form, $this->contact_form_transformer);
    }
}
