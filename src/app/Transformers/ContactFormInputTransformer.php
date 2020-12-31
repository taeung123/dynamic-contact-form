<?php
namespace VCComponent\Laravel\ConfigContact\Transformer;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Transformer\ContactFormInputItemTransformer;
use VCComponent\Laravel\ConfigContact\Transformer\ContactFormInputValidationTransformer;

class ContactFormInputTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'contactFormInputItems',
        'contactFormInputValidations',
    ];

    public function transform(ContactFormInput $model)
    {
        return [
            'id'              => $model->id,
            'contact_form_id' => $model->contact_form_id,
            'type_input'      => $model->type_input,
            'label'           => $model->label,
            'slug'            => $model->slug,
            'order'           => $model->order,
            'note'            => $model->note,
            'placeholder'     => $model->placeholder,
            'timestamp'       => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeContactFormInputItems(ContactFormInput $model)
    {
        return $this->collection($model->contactFormInputItems, new ContactFormInputItemTransformer);
    }

    public function includeContactFormInputValidations(ContactFormInput $model)
    {
        return $this->collection($model->contactFormInputValidations, new ContactFormInputValidationTransformer);
    }
}
