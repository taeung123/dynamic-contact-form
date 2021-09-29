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
    public function should_not_get_contact_form_input_item_not_exists_admin()
    {
        factory(ContactFormInput::class)->create();
        $response = $this->json('GET', 'api/admin/contact-form-input/2');
        $response->assertStatus(500);
        $response->assertJson(['message' => 'Server Error']);
    }
    /**
     * @test
     */
    public function should_get_contact_form_input_item_admin()
    {
        $contact_form_input = factory(ContactFormInput::class)->create();
        $response = $this->json('GET', 'api/admin/contact-form-input/' . $contact_form_input->id);
        $response->assertStatus(200);
        $response->assertJson([
            'type_input' => $contact_form_input->type_input,
            'slug' => $contact_form_input->slug,
        ]);
    }

    /**
     * @test
     */
    public function should_not_create_contact_form_input_required_admin()
    {
        $data = factory(ContactFormInput::class)->make(['contact_form_id' => '', 'type_input' => '', 'label' => ''])->toArray();
        $response = $this->json('POST', 'api/admin/contact-form-input', $data);
        $this->assertValidation($response, 'label', "The label field is required.");
        $this->assertValidation($response, 'contact_form_id', "The contact form id field is required.");
        $this->assertValidation($response, 'type_input', "The type input field is required.");

    }
    /**
     * @test
     */
    public function should_not_create_contact_form_input_exists_admin()
    {
        factory(ContactForm::class)->create();
        factory(ContactFormInput::class)->create(['label' => 'input test', 'slug' => 'input_test']);
        $data = factory(ContactFormInput::class)->make(['label' => 'input test', 'slug' => 'input_test'])->toArray();
        $response = $this->json('POST', 'api/admin/contact-form-input', $data);
        $this->assertInputAlreadyExist($response);
    }

    /**
     * @test
     */
    public function should_create_contact_form_input_admin()
    {
        factory(ContactForm::class)->create();
        $data = factory(ContactFormInput::class)->make()->toArray();
        $response = $this->json('POST', 'api/admin/contact-form-input', $data);
        $response->assertStatus(200);
        $response->assertJson(['label' => $data['label']]);
        $this->assertDatabaseHas('contact_form_inputs', $data);
    }
    /**
     * @test
     */
    public function should_not_update_contact_form_input_required_admin()
    {
        factory(ContactForm::class)->create();
        $contact_form_input = factory(ContactFormInput::class)->create();
        $id = $contact_form_input->id;
        $contact_form_input->contact_form_id = '';
        $contact_form_input->type_input = '';
        $contact_form_input->label = '';
        $response = $this->json('PUT', 'api/admin/contact-form-input/' . $id, $contact_form_input->toArray());
        $this->assertValidation($response, 'label', "The label field is required.");
        $this->assertValidation($response, 'contact_form_id', "The contact form id field is required.");
        $this->assertValidation($response, 'type_input', "The type input field is required.");
        $this->assertDeleted('contact_form_inputs', $contact_form_input->toArray());

    }

    /**
     * @test
     */
    public function should_not_update_contact_form_input_exists_admin()
    {
        factory(ContactForm::class)->create();
        factory(ContactFormInput::class)->create(['label' => 'input test', 'slug' => 'input_test']);
        $contact_form_input = factory(ContactFormInput::class)->create();
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
    }

    /**
     * @test
     */
    public function should_update_contact_form_input_admin()
    {
        factory(ContactForm::class)->create();
        $contact_form_input = factory(ContactFormInput::class)->make();
        $contact_form_input->save();
        unset($contact_form_input['updated_at']);
        unset($contact_form_input['created_at']);

        $id = $contact_form_input->id;
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
        $response = $this->json('DELETE', 'api/admin/contact-form-input/' . $contactFormInput['id']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDeleted('contact_form_inputs', $contactFormInput);

    }

}
