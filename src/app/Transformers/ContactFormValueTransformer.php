<?php
namespace VCComponent\Laravel\ConfigContact\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormValue;

class ContactFormValueTransformer extends TransformerAbstract
{
    public function transform(ContactFormValue $model)
    {
        return [
            'id'              => $model->id,
            'contact_form_id' => $model->contact_form_id,
            'payload'         => json_decode($model->payload),
            'timestamp'       => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
}
