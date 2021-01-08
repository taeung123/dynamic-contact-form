<?php
namespace VCComponent\Laravel\ConfigContact\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Transformer\ContactFormInputTransformer;

class ContactFormTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'contactFormInputs',
    ];

    public function transform(ContactForm $model)
    {
        return [
            'id'        => $model->id,
            'name'      => $model->name,
            'slug'      => $model->slug,
            'status'    => $model->status,
            'timestamp' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeContactFormInputs(ContactForm $model)
    {
        return $this->collection($model->contactFormInputs, new ContactFormInputTransformer);
    }
}
