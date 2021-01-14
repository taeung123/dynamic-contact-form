<?php
namespace VCComponent\Laravel\ConfigContact\Transformer;

use League\Fractal\TransformerAbstract;

class ContactFormInputItemTransformer extends TransformerAbstract
{
    public function transform($model)
    {
        return [
            'id'                    => $model->id,
            'contact_form_input_id' => $model->contact_form_input_id,
            'label'                 => $model->label,
            'value'                 => $model->value,
            'timestamp'             => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
