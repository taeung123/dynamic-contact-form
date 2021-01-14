<?php
namespace VCComponent\Laravel\ConfigContact\Traits;

trait RenderFormMethods
{
    use Helpers;

    public function renderContactForm($page, $position)
    {

        $contact_form = $this->where(['status' => 1, 'page' => $page, 'position' => $position])->with(['contactFormInputs' => function ($q) {
            $q->orderBy('order')->with(['contactFormInputItems' => function ($q) {
                $q->orderBy('order');
            }]);
        }])->latest()->first();

        if ($contact_form) {

            echo view('contact_form::form.header-form', ['contact_form' => $contact_form]);

            foreach ($contact_form->contactFormInputs as $input) {
                if ($input->type_input === "text") {
                    echo view('contact_form::inputs.text', ['input' => $input]);
                }

                if ($input->type_input === "select") {
                    echo view('contact_form::inputs.select', ['input' => $input]);
                }

                if ($input->type_input === "radio") {
                    $name = $this->changeLabelToSlug($input->label);
                    echo view('contact_form::inputs.radio', ['input' => $input, 'name' => $name]);
                }

                if ($input->type_input === "textarea") {
                    echo view('contact_form::inputs.textarea', ['input' => $input]);
                }

                if ($input->type_input === "checkbox") {
                    echo view('contact_form::inputs.checkbox', ['input' => $input]);
                }

                if ($input->type_input === "date") {
                    echo view('contact_form::inputs.date', ['input' => $input]);
                }
            }

            echo view('contact_form::form.footer-form');
        }
    }
}
