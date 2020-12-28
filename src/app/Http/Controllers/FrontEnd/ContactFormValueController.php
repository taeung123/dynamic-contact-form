<?php

namespace VCComponent\Laravel\ConfigContact\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Repositories\ContactFormValueRepository;
use VCComponent\Laravel\ConfigContact\Validators\ContactFormValueValidation;

class ContactFormValueController extends Controller
{
    protected $contact_form_value_repository;
    protected $contact_form_value_validation;
    public function __construct(ContactFormValueRepository $contact_form_value_repository, ContactFormValueValidation $contact_form_value_validation)
    {
        $this->contact_form_value_repository = $contact_form_value_repository;
        $this->contact_form_value_validation = $contact_form_value_validation;
    }
    public function store(Request $request)
    {
        $this->contact_form_value_validation->isValid($request);

        $data = $request->all();
        array_shift($data);
        $array_labels = [];
        $array_values = [];

        foreach (array_keys($data) as $value) {
            $query_get_label = ContactFormInput::select('label')->where('slug', $value)->get();
            foreach ($query_get_label as $key) {
                array_push($array_labels, $key->label);
            }
        }

        foreach ($request->all() as $value) {
            $array_values[] = $value;
        }

        array_shift($array_values);
        $payload                                    = array_combine($array_labels, $array_values);
        $contact_form_value_data['contact_form_id'] = $request->contact_form_id;
        $contact_form_value_data['payload']         = json_encode($payload);
        $this->contact_form_value_repository->create($contact_form_value_data);

        $message = "Thông tin liên hệ đã được gửi";
        return redirect()->back()->with('success', $message);
    }
}
