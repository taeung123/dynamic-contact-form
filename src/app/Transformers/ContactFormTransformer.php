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
            'id'          => $model->id,
            'name'        => $model->name,
            'slug'        => $model->slug,
            'status'      => $model->status,
            'page'        => $model->page,
            'position'    => $model->position,
            'page_vn'     => $this->translatePage($model->page),
            'position_vn' => $this->translatePosition($model->page, $model->position),
            'timestamp'   => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }

    public function includeContactFormInputs(ContactForm $model)
    {
        return $this->collection($model->contactFormInputs, new ContactFormInputTransformer);
    }

    public function translatePage($page)
    {
        foreach (config('dynamic-contact-form.page') as $key => $value) {
            if ($page === $key) {
                return $value['label'];
            }
        }
    }

    public function translatePosition($page, $position)
    {
        foreach (config('dynamic-contact-form.page') as $key => $value) {
            if ($page === $key) {
                foreach ($value['position'] as $key => $value) {
                    if ($position === $key) {
                        return $value;
                    }
                }
            }
        }
    }
}
