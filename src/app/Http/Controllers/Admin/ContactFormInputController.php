<?php
namespace VCComponent\Laravel\ConfigContact\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputItemRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputValidationRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormInputItemValidation;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormInputVaidationValidation;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormInputValidation;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

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
        ContactFormInputVaidationValidation $contact_form_input_validation_validation
    ) {
        $this->contact_form_repository                  = $contact_form_repository;
        $this->contact_form_input_repository            = $contact_form_input_repository;
        $this->contact_form_input_validation_repository = $contact_form_input_Validation_repository;
        $this->contact_form_input_item_repository       = $contact_form_input_item_repository;
        $this->contact_form_input_validation            = $contact_form_input_validation;
        $this->contact_form_input_item_validation       = $contact_form_input_item_validation;
        $this->contact_form_input_validation_validation = $contact_form_input_validation_validation;

    }

    public function createInput(Request $request)
    {
        $this->contact_form_input_validation->isValid($request, 'RULE_CREATE');

        $data = $request->all();

        $label_array = $this->contact_form_input_repository->pluck('label');
        if (in_array($data['label'], $label_array->toArray())) {
            throw new Exception('Input label đã tồn tại, nhập label khác');
        }

        $data['slug']       = $this->changeLabelToSlug($data['label']);
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

    public function updateInput(Request $request, $id)
    {
        $this->contact_form_input_validation->isValid($request, 'RULE_UPDATE');

        $data = $request->all();

        $label_current      = $this->contact_form_input_repository->find($id)->label;
        $check_label_exists = $this->contact_form_input_repository
            ->select('label')
            ->where('label', '=', $data['label'])
            ->where('label', '!=', $label_current)
            ->exists();
        if ($check_label_exists) {
            throw new Exception('Input label tồn tại, nhập label khác');
        }

        $data['slug'] = $this->changeLabelToSlug($data['label']);
        $this->contact_form_input_repository->update($data, $id);

        if (isset($data["contactFormInputItems"])) {
            $contact_form_input = $this->contact_form_input_repository->find($id);
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
                        'slug'  => $this->changeLabelToSlug($item['label']),
                        'value' => $item["value"],
                    ]
                );
            }
        }

        if (isset($data["contactFormInputValidations"])) {
            $contact_form_input = $this->contact_form_input_repository->find($id);
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

        $data_response = $this->contact_form_input_repository->with(['contactFormInputItems', 'contactFormInputValidations'])->find($id)->toArray();
        return response()->json($data_response);
    }

    public function deleteInput($id)
    {
        $this->contact_form_input_repository->destroy($id);
        return $this->success();
    }
}
