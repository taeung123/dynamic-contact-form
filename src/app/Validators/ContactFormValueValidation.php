<?php

namespace VCComponent\Laravel\ConfigContact\Validators;

use Illuminate\Http\Request;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Traits\Helpers;

class ContactFormValueValidation
{
    use Helpers;

    public function isValid(Request $request)
    {
        $data = $request->all();
        array_shift($data);
        $array_rules         = [];
        $array_alerts        = [];
        $array_rule_keys     = [];
        $array_rule_values   = [];
        $array_rule_values_2 = [];

        $index = 0;
        foreach(array_keys($data) as $key){
            $array_rules[] = $key;
        }
        foreach (array_keys($data) as $value) {

            $get_validations = ContactFormInput::with('contactFormInputValidations')->where('slug', $value)->first();

            if ($get_validations->contactFormInputValidations->count() > 0) {
                $array_rule_keys[] = $get_validations->slug;

                foreach ($get_validations->contactFormInputValidations as $key => $validation) {
                    $key_after_change                = $this->changeKey($get_validations->slug . "." . $validation->validation_name);
                    $array_alerts[$key_after_change] = $get_validations->label . " " . $validation->validation_value;
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
        $validatedData = $request->validate(
            $array_rules,
            $array_alerts
        );
    }
}
