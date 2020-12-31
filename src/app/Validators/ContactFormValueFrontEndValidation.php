<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;

class ContactFormValueFrontEndValidation
{
    use Helpers;

    public function isValid(Request $request)
    {
        $data            = $request->all();
        $id_contact_form = $data['contact_form_id'];
        array_shift($data);
        $array_rules   = $this->getRuleValidate($data, $id_contact_form);
        $array_alerts  = $this->getAlertValidate($data, $id_contact_form);
        $validatedData = $request->validate(
            $array_rules,
            $array_alerts
        );
    }

    public function getRuleValidate($data, $id_contact_form)
    {
        $array_rules = [];
        $index       = 0;
        foreach (array_keys($data) as $value) {
            $get_validations = ContactFormInput::whereHas('contactForm', function ($q) use ($id_contact_form) {
                $q->where('id', $id_contact_form);
            })->with('contactFormInputValidations')->where('slug', $value)->first();
            if ($get_validations->contactFormInputValidations->count() > 0) {
                $array_rule_keys[] = $get_validations->slug;
                foreach ($get_validations->contactFormInputValidations as $key => $validation) {
                    $array_rule_values[$index][$key] = $validation->validation_name;
                }
                $index++;
                $index_2 = 0;
                foreach ($array_rule_values as $value) {
                    if (count($value) === 1) {
                        $array_rule_values_2[$index_2] = $value;
                    }
                    $array_rule_values_2[$index_2] = implode("|", $value);
                    $index_2++;
                }
                $array_rules = array_combine($array_rule_keys, $array_rule_values_2);
            }
        }

        return $array_rules;
    }

    public function getAlertValidate($data, $id_contact_form)
    {
        $array_alerts = [];
        foreach (array_keys($data) as $value) {
            $get_validations = ContactFormInput::whereHas('contactForm', function ($q) use ($id_contact_form) {
                $q->where('id', $id_contact_form);
            })->with('contactFormInputValidations')->where('slug', $value)->first();
            if ($get_validations->contactFormInputValidations->count() > 0) {
                foreach ($get_validations->contactFormInputValidations as $key => $validation) {
                    $key_after_change                = $this->changeKey($get_validations->slug . "." . $validation->validation_name);
                    $array_alerts[$key_after_change] = $get_validations->label . " " . $validation->validation_value;
                }
            }
        }
        return $array_alerts;
    }

}
