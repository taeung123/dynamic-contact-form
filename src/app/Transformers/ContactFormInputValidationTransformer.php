<?php
namespace VCComponent\Laravel\ConfigContact\Transformer;

use League\Fractal\TransformerAbstract;

class ContactFormInputValidationTransformer extends TransformerAbstract
{
    public function transform($model)
    {
        return [
            'id'                    => $model->id,
            'contact_form_input_id' => $model->contact_form_input_id,
            'validation_name'       => $model->validation_name,
            'validation_value'      => $model->validation_value,
            'timestamp'             => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
