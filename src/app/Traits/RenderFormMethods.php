<?php
namespace VCComponent\Laravel\ConfigContact\Traits;

trait RenderFormMethods
{
    use Helpers;

    public function renderContactForm()
    {
        $contact_forms = $this->where('status', 1)->with(['contactFormInputs' => function ($q) {
            $q->orderBy('order')->with(['contactFormInputItems' => function ($q) {
                $q->orderBy('order');
            }]);
        }])->get();

        foreach ($contact_forms as $contact_form) {
            $html_head = '<form action="' . route('send') . '" method="POST" role="form" class="' . $contact_form->slug . '">';
            $html_head .= '<legend>' . $contact_form->name . '</legend>';
            $html_head .= '<input name="contact_form_id" value="' . $contact_form->id . '" hidden> </input>';
            echo $html_head;

            foreach ($contact_form->contactFormInputs as $input) {
                if ($input->type_input === "text") {
                    echo $this->renderInputText($input);
                }
                if ($input->type_input === "select") {
                    echo $this->renderInputSelect($input);
                }
                if ($input->type_input === "radio") {
                    echo $this->renderInputRadio($input);
                }
                if ($input->type_input === "textarea") {
                    echo $this->renderInputTextArea($input);
                }
                if ($input->type_input === "checkbox") {
                    echo $this->renderInputCheckBox($input);
                }
                if ($input->type_input === "date") {
                    echo $this->renderInputDate($input);
                }
                if($input->type_input === "comboInputDate"){
                    echo $this->renderComboInputDate($input);
                }
            }
            $html_foot = '<button type="submit" class="btn btn-primary">Gửi</button></form>';
            echo $html_foot;
        }
    }

    public function renderInputText($input)
    {
        $html = '<div class="form-group ' . $input->slug . '">';
        $html .= '<label for="' . $input->slug . '">' . $input->label . ':</label>';
        $html .= '<input type="text" class="form-control" name=' . $input->slug . ' id="' . $input->slug . '" placeholder="' . $input->placeholder . '"';
        $html .= '"><small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small></div>';
        return $html;
    }

    public function renderInputSelect($input)
    {
        $html = '<div class="form-group ' . $input->slug . '">';
        $html .= '<label for="' . $input->slug . '">' . $input->label . ':</label>';
        $html .= '<select name="' . $input->slug . '" id="' . $input->slug . '" class="form-control">';
        if ($input->contactFormInputItems->count() > 0) {
            foreach ($input->contactFormInputItems as $item) {
                $html .= '<option value="' . $item->value . '">' . $item->label . '</option>';
            }
        }
        $html .= '</select><small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small></div>';
        return $html;
    }

    public function renderInputRadio($input)
    {
        $name = $this->changeLabelToSlug($input->label);
        $html = '<div class="radio ' . $input->slug . '">';
        $html .= '<label>' . $input->label . ':<label>';
        if ($input->contactFormInputItems->count() > 0) {
            $first = true;
            foreach ($input->contactFormInputItems as $item) {
                if ($first) {
                    $html .= ' <label>';
                    $html .= '<input type="radio" name="' . $name . '" id="' . $item->slug . '" value="' . $item->value . '" checked>';
                    $html .= $item->label;
                    $html .= '</label>';
                } else {
                    $html .= ' <label>';
                    $html .= '<input type="radio" name="' . $name . '" id="' . $item->slug . '" value="' . $item->value . '">';
                    $html .= $item->label;
                    $html .= '</label>';
                }
            }
        }
        $html .= '</div><small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small>';
        return $html;
    }

    public function renderInputCheckBox($input)
    {
        $html = '<div class="form-group ' . $input->slug . '">';
        $html .= '<label for="">' . $input->label . ':</label>';
        $html .= '<div class="checkbox">';
        if ($input->contactFormInputItems->count() > 0) {
            foreach ($input->contactFormInputItems as $item) {
                $html .= '<label>';
                $html .= '<input type="checkbox" name="' . $input->slug . '[]" value="' . $item->value . '">';
                $html .= $item->label;
                $html .= '</label>';
            }
        }
        $html .= '<input type="checkbox" name="' . $input->slug . '[]" value="" hidden checked>';
        $html .= '</div><small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small>';
        $html .= '</div>';
        return $html;
    }

    public function renderInputTextArea($input)
    {
        $html = '<div class="form-group ' . $input->slug . '">';
        $html .= '<label for="">' . $input->label . ':</label>';
        $html .= '<textarea id="' . $input->slug . '" class="form-control" name="' . $input->slug . '" placeholder="' . $input->placeholder . '"></textarea>';
        $html .= '<small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small></div>';
        return $html;
    }

    public function renderInputDate($input)
    {
        $html = '<div class="form-group ' . $input->slug . '">';
        $html .= '<label for="' . $input->slug . '">' . $input->label . ':</label>';
        $html .= '<input type="text" class="form-control"  onfocus="(this.type=' . "'date'" . ')" onblur="(this.type=' . "'text'" . ')"  name=' . $input->slug . ' id="' . $input->slug . '" placeholder="' . $input->placeholder . '"';
        $html .= '><small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small></div>';
        return $html;
    }
    public function renderComboInputDate($input)
    {
        $html = '<div class="form-group ' . $input->slug . '">';
        $html .= '<label for="' . $input->slug . '">' . $input->label . ':</label>';
        $html .= '<input type="text" class="form-control"  onfocus="(this.type=' . "'date'" . ')" onblur="(this.type=' . "'text'" . ')"  name=' . $input->slug . ' id="' . $input->slug . '" placeholder="' . $input->placeholder . '"';
        $html .= '><small id="' . $input->slug . '" class="form-text text-muted">
        ' . $input->note . '
        </small></div>';
        return $html;
    }
}
