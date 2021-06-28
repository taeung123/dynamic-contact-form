<?php

namespace VCComponent\Laravel\ConfigContact\Http\Controllers\FrontEnd;

use VCComponent\Laravel\ConfigContact\Events\DynamicFormSubmittedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormInputRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormRepository;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormValueFrontEndValidation;

class ContactFormValueFrontEndController extends Controller
{
    protected $contact_form_value_repository;
    protected $contact_form_value_front_end_validation;
    protected $contact_form_repository;
    protected $contact_form_entity;
    protected $contact_form_input_entity;
    public function __construct(
        ContactFormValueRepository $contact_form_value_repository,
        ContactFormValueFrontEndValidation $contact_form_value_front_end_validation,
        ContactFormRepository $contact_form_repository,
        ContactFormInputRepository $contact_form_input
    ) {
        $this->contact_form_value_repository           = $contact_form_value_repository;
        $this->contact_form_value_front_end_validation = $contact_form_value_front_end_validation;
        $this->contact_form_entity                     = $contact_form_repository->getEntity();
        $this->contact_form_input_entity               = $contact_form_input->getEntity();
    }

    public function store(Request $request)
    {
        $this->contact_form_value_front_end_validation->isValid($request);

        $data            = $request->all();
        $contact_form_id = $data['contact_form_id'];
        array_shift($data);
        $array_labels = [];
        $array_values = [];

        foreach (array_keys($data) as $value) {
            $query_get_label = $this->contact_form_input_entity::select('label')->whereHas('contactForm', function ($q) use ($contact_form_id) {
                $q->where('id', $contact_form_id);
            })->where('slug', $value)->get();

            foreach ($query_get_label as $key) {
                array_push($array_labels, $key->label);
            }
        }

        if ($request->hasFile('tep_dinh_kem')) {
            $file      = $request->tep_dinh_kem;
            $file_name = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('/uploads/files'), $file_name);
            $http                 = $_SERVER['HTTP_ORIGIN'];
            $data['tep_dinh_kem'] = $http . '/uploads/files/' . $file_name;
        }

        foreach ($data as $value) {
            $array_values[] = $value;
        }

        $payload                                    = array_combine($array_labels, $array_values);
        $contact_form_value_data['contact_form_id'] = $request->contact_form_id;
        $contact_form_value_data['payload']         = json_encode($payload);
        $contact_form_value_data['status']          = "2";

        $contact_form_value = $this->contact_form_value_repository->create($contact_form_value_data);
        $contact_form_data = $this->contact_form_entity->where('id', $contact_form_id)->first();
        event(new DynamicFormSubmittedEvent($contact_form_data, $contact_form_value));

        $message = $this->contact_form_entity->select('success_notification_content')->where('id', $contact_form_id)->first()->success_notification_content;
        return redirect()->back()->with('success', $message);
    }
}
