<?php

namespace VCComponent\Laravel\ConfigContact\Test\Feature\Web;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInputValidation;
use VCComponent\Laravel\ConfigContact\Test\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_create_contact_form_web()
    {
        factory(ContactForm::class)->create();
        factory(ContactFormInput::class)->create(
            [
                'contact_form_id' => 1,
                'type_input' => 'text',
                'label' => 'Họ tên',
                'slug' => 'ho_ten',
                'order' => 1,
                'note' => '',
                'placeholder' => 'Họ tên',
            ],
        );
        ContactFormInputValidation::create(
            [
                'contact_form_input_id' => 1,
                'validation_name' => 'required',
                'validation_value' => 'Bạn chưa nhập trường này',
            ],
        );

        $data = [
            'contact_form_id' => 1,
            'ho_ten' => '',
        ];
        $response = $this->call('POST', 'send-contact-infor', $data);
        $response->assertSessionHasErrors([
            'ho_ten' => 'Họ tên Bạn chưa nhập trường này',
        ]);
        $this->assertDeleted('contact_form_values', $data);

        $data = [
            'contact_form_id' => 1,
            'ho_ten' => 'test',
        ];
        $response = $this->call('POST', 'send-contact-infor', $data);
        $test = [
            'payload' => json_encode(['Họ tên' => 'test']),
        ];
        $this->assertDatabaseHas('contact_form_values', $test);

    }

}
