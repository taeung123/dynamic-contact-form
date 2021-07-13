<?php

namespace VCComponent\Laravel\ConfigContact\Test\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormInput;
use VCComponent\Laravel\ConfigContact\Test\TestCase;

class ContactFormInputTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_get_contact_form_input_item_admin()
    {
        $contact_form_input = factory(ContactFormInput::class)->create();
        $response = $this->call('GET', 'api/admin/contact-form-input/' . $contact_form_input->id);
        $response->assertStatus(200);
        $response->assertJson([
            'type_input' => $contact_form_input->type_input,
            'slug' => $contact_form_input->slug,
        ]);
    }

    /**
     * @test
     */
    public function should_create_contact_form_input_admin()
    {
        factory(ContactForm::class)->create();
        factory(ContactFormInput::class)->create(['label' => 'input test', 'slug' => 'input_test']);
        $data = factory(ContactFormInput::class)->make(['label' => 'input test', 'slug' => 'input_test'])->toArray();
        $response = $this->json('POST', 'api/admin/contact-form-input', $data);
        $this->assertInputAlreadyExist($response);
        $data['label'] = '';
        $response = $this->json('POST', 'api/admin/contact-form-input', $data);
        $this->assertValidation($response, 'label', "The label field is required.");

        $data = factory(ContactFormInput::class)->make()->toArray();
        $response = $this->json('POST', 'api/admin/contact-form-input', $data);
        $response->assertStatus(200);
        $response->assertJson(['label' => $data['label']]);
        $this->assertDatabaseHas('contact_form_inputs', $data);
    }

    /**
     * @test
     */
    public function should_update_contact_form_input_admin()
    {
        factory(ContactForm::class)->create();
        factory(ContactFormInput::class)->create(['label' => 'input test', 'slug' => 'input_test']);
        $contact_form_input = factory(ContactFormInput::class)->make();
        $contact_form_input->save();
        unset($contact_form_input['updated_at']);
        unset($contact_form_input['created_at']);

        $id = $contact_form_input->id;
        $contact_form_input->label = 'input test';
        $data = $contact_form_input->toArray();
        $response = $this->json('PUT', 'api/admin/contact-form-input/' . $id, $data);
        $response->assertJsonFragment([
            'notifcation' => 'Input label already exists',
        ]);
        $this->assertDeleted('contact_form_inputs', $data);

        $contact_form_input->label = 'input update';
        $data = $contact_form_input->toArray();
        $response = $this->json('PUT', 'api/admin/contact-form-input/' . $id, $data);
        $response->assertStatus(200);
        $data['slug'] = 'input_update';
        $this->assertDatabaseHas('contact_form_inputs', $data);
    }

    /**
     * @test
     */
    public function should_soft_delete_contact_form_input_admin()
    {
        $contactFormInput = factory(ContactFormInput::class)->create()->toArray();
        unset($contactFormInput['updated_at']);
        unset($contactFormInput['created_at']);
        $this->assertDatabaseHas('contact_form_inputs', $contactFormInput);
        $response = $this->call('DELETE', 'api/admin/contact-form-input/' . $contactFormInput['id']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDeleted('contact_form_inputs', $contactFormInput);

    }

}
