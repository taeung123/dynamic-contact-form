<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputItemRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputValidationRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;
use VCComponent\Laravel\ConfigContact\Transformer\ContactFormInputTransformer;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormInputItemValidation;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormInputValidation;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormInputValidationValidation;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\PermissionDeniedException;

class ContactFormInputController extends ApiController
{
    use Helpers;

    protected $contact_form_input_repository;
    protected $contact_form_input_validation_repository;
    protected $contact_form_input_item_repository;
    protected $contact_form_input_item_validation;
    protected $contact_form_input_validation;
    protected $contact_form_repository;
    protected $contact_form_input_validation_validation;

    public function __construct(
        ContactFormRepository $contact_form_repository,
        ContactFormInputRepository $contact_form_input_repository,
        ContactFormInputValidationRepository $contact_form_input_Validation_repository,
        ContactFormInputItemRepository $contact_form_input_item_repository,
        ContactFormInputItemValidation $contact_form_input_item_validation,
        ContactFormInputValidation $contact_form_input_validation,
        ContactFormInputValidationValidation $contact_form_input_validation_validation,
        ContactFormInputTransformer $contact_form_input_transformer
    ) {
        $this->contact_form_repository                  = $contact_form_repository;
        $this->contact_form_input_repository            = $contact_form_input_repository;
        $this->contact_form_input_validation_repository = $contact_form_input_Validation_repository;
        $this->contact_form_input_item_repository       = $contact_form_input_item_repository;
        $this->contact_form_input_validation            = $contact_form_input_validation;
        $this->contact_form_input_item_validation       = $contact_form_input_item_validation;
        $this->contact_form_input_validation_validation = $contact_form_input_validation_validation;
        $this->contact_form_input_entity                = $this->contact_form_input_repository->getEntity();
        $this->contact_form_input_transformer           = $contact_form_input_transformer;

        if (!empty(config('dynamic-contact-form.auth_middleware.admin'))) {
            $user = $this->getAuthenticatedUser();
            if (Gate::forUser($user)->denies('manage', $this->contact_form_input_entity)) {
                throw new PermissionDeniedException();
            }
            foreach (config('dynamic-contact-form.auth_middleware.admin') as $middleware) {
                $this->middleware($middleware['middleware'], ['except' => $middleware['except']]);
            }
        }
    }

    public function show($id)
    {
        $contact_form_input = $this->contact_form_input_entity->find($id);

        if (!$contact_form_input) {
            throw new Exception("Input does not exist");
        }

        $contact_form_input = $this->contact_form_input_repository->find($id);

        return $this->response->item($contact_form_input, $this->contact_form_input_transformer);
    }
    public function store(Request $request)
    {
        $this->contact_form_input_validation->isValid($request, 'RULE_CREATE');

        $data              = $request->all();
        // dd($data);
        $data['slug']      = $this->changeLabelToSlug($data['label']);
        $check_slug_exists = $this->contact_form_input_repository->checkBySlug($data['contact_form_id'], $data['slug']);

        if ($check_slug_exists) {
            $data_response = [
                'data' => ['status' => 'error', 'notifcation' => 'Input label already exists', 'contact_form_id' => $data['contact_form_id']],
            ];
            return response()->json($data_response);
        }

        $contact_form_input = new ContactFormInput($data);
        $contact_form       = $this->contact_form_repository->find($request->contact_form_id);
        $query              = $contact_form->contactFormInputs()->create($contact_form_input->toArray());

        $contact_form_input = $this->contact_form_input_repository->find($query->id);

        if (isset($data["contactFormInputItems"])) {
            foreach ($data["contactFormInputItems"] as $item) {
                $this->contact_form_input_item_validation->isValid($item, 'RULE_CREATE');
                $item['slug'] = $this->changeLabelToSlug($item['label']);
                $contact_form_input->contactFormInputItems()->create($item);
            }
        }

        if (isset($data["contactFormInputValidations"])) {
            foreach ($data["contactFormInputValidations"] as $item) {
                $this->contact_form_input_validation_validation->isValid($item, 'RULE_CREATE');
                $contact_form_input->contactFormInputValidations()->create($item);
            }
        }

        $data_response = $this->contact_form_input_repository->with(['contactFormInputItems', 'contactFormInputValidations'])->find($query->id)->toArray();

        return response()->json($data_response);
    }

    public function update(Request $request, $id)
    {
        $this->contact_form_input_validation->isValid($request, 'RULE_UPDATE');

        $contact_form_input = $this->contact_form_input_entity->find($id);
        if (!$contact_form_input) {
            throw new Exception("Input does not exist");
        }

        $data              = $request->all();

        $slug_current      = $this->contact_form_input_repository->find($id)->slug;
        $slug              = $this->changeLabelToSlug($data['label']);
        $check_slug_exists = $this->contact_form_input_repository->checkBySlug($data['contact_form_id'], $slug, $slug_current);

        if ($check_slug_exists) {
            $data_response = [
                'data' => ['status' => 'error', 'notifcation' => 'Input label already exists', 'contact_form_id' => $data['contact_form_id']],
            ];
            return response()->json($data_response);
        }

        $contact_form_input = ContactFormInput::find($id);
        $data['slug']       = $slug;
        $contact_form_input->update($data);

        $contact_form_input = ContactFormInput::find($id);

        if (isset($data["contactFormInputItems"])) {

            $contact_form_input->contactFormInputItems()->delete();

            foreach ($data["contactFormInputItems"] as $item) {
                $this->contact_form_input_item_validation->isValid($item, 'RULE_UPDATE');
                $item['slug'] = $this->changeLabelToSlug($item['label']);
                if (!isset($item['order'])) {
                    $item['order'] = 1;
                }
                $contact_form_input->contactFormInputItems()->updateOrCreate(
                    [
                        "id" => $item["id"],
                    ],
                    [
                        'label' => $item['label'],
                        "order" => $item['order'],
                        'slug'  => $item['slug'],
                        'value' => $item["value"],
                    ]
                );
            }
        }

        if (isset($data["contactFormInputValidations"])) {

            $contact_form_input->contactFormInputValidations()->delete();

            foreach ($data["contactFormInputValidations"] as $item) {
                $this->contact_form_input_validation_validation->isValid($item, 'RULE_UPDATE');
                $contact_form_input->contactFormInputValidations()->updateOrCreate(
                    [
                        "id" => $item["id"],
                    ],
                    [
                        'validation_name'  => $item['validation_name'],
                        "validation_value" => $item['validation_value'],
                    ]
                );
            }
        }

        $data_response = ContactFormInput::with(['contactFormInputItems', 'contactFormInputValidations'])->find($id)->toArray();

        return response()->json($data_response);
    }

    public function destroy($id)
    {
        $contact_form_input = $this->contact_form_input_entity->find($id);

        if (!$contact_form_input) {
            throw new Exception("Input does not exist");
        }

        $contact_form_input->destroy($id);

        return $this->success();
    }
}
