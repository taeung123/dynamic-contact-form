<?php

namespace VCComponent\Laravel\ConfigContact\Test\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Test\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_get_contact_form_list_paginate_admin()
    {
        $contact_forms = factory(ContactForm::class, 5)->create();
        $contact_forms = $contact_forms->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();
        $listIds = array_column($contact_forms, 'id');
        array_multisort($listIds, SORT_DESC, $contact_forms);

        $response = $this->json('GET', 'api/admin/contact-form');
        $response->assertStatus(200);
        $response->assertJson(['data' => $contact_forms]);
    }

    /**
     * @test
     */
    public function should_create_contact_form_admin()
    {
        factory(ContactForm::class)->create(['name' => 'contact form test', 'slug' => 'contact_form_test']);

        $data = factory(ContactForm::class)->make(['name' => 'contact form test', 'slug' => 'contact_form_test'])->toArray();
        $response = $this->json('POST', 'api/admin/contact-form', $data);
        $this->assertAlreadyExist($response);

        $data = factory(ContactForm::class)->make(['name' => ''])->toArray();
        $response = $this->json('POST', 'api/admin/contact-form', $data);
        $this->assertValidation($response, 'name', "The name field is required.");

        $data = factory(ContactForm::class)->make()->toArray();
        $response = $this->json('POST', 'api/admin/contact-form', $data);
        $response->assertStatus(200);
        $this->assertDatabaseHas('contact_forms', $data);
    }
    /**
     * @test
     */
    public function should_update_contact_form_admin()
    {
        factory(ContactForm::class)->create(['name' => 'contact form test', 'slug' => 'contact_form_test']);

        $contact_form = factory(ContactForm::class)->make();
        $contact_form->save();
        unset($contact_form['updated_at']);
        unset($contact_form['created_at']);

        $id = $contact_form->id;
        $contact_form->name = 'contact form test';
        $data = $contact_form->toArray();
        $response = $this->json('PUT', 'api/admin/contact-form/' . $id, $data);
        $response->assertStatus(500);
        $response->assertJsonFragment(['message' => 'Server Error']);

        $contact_form->name = "update name";
        $data = $contact_form->toArray();
        $response = $this->json('PUT', 'api/admin/contact-form/' . $id, $data);
        $response->assertStatus(200);
        $response->assertJson([
            'name' => $data['name'],
        ]);
        $data['slug'] = 'update_name';
        $this->assertDatabaseHas('contact_forms', $data);
    }
    /**
     * @test
     */
    public function should_soft_delete_contact_form_admin()
    {
        $contactForm = factory(ContactForm::class)->create()->toArray();
        unset($contactForm['updated_at']);
        unset($contactForm['created_at']);
        $this->assertDatabaseHas('contact_forms', $contactForm);
        $response = $this->call('DELETE', 'api/admin/contact-form/' . $contactForm['id']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDeleted('contact_forms', $contactForm);

    }

    /**
     * @test
     */
    public function should_get_contact_form_item_admin()
    {
        $contactForm = factory(ContactForm::class)->create();
        unset($contactForm['updated_at']);
        unset($contactForm['created_at']);
        $response = $this->call('GET', 'api/admin/contact-form/' . $contactForm->id);
        $response->assertStatus(200);
        $response->assertJson([
            'name' => $contactForm->name,
            'slug' => $contactForm->slug,
            'status' => $contactForm->status,
        ]);
    }
    /**
     * @test
     */
    public function should_update_status_contact_form_admin()
    {
        $contactForm = factory(ContactForm::class)->create()->toArray();
        unset($contactForm['updated_at']);
        unset($contactForm['created_at']);
        $this->assertDatabaseHas('contact_forms', $contactForm);
        $data = ['status' => 2];
        $response = $this->json('PUT', 'api/admin/contact-form/' . $contactForm['id'] . '/change-status', $data);
        $response->assertStatus(200);
        $response = $this->json('GET', 'api/admin/contact-form/' . $contactForm['id']);
        $response->assertJson(['status' => 2]);
    }
    /**
     * @test
     */
    public function should_get_contact_form_list_admin()
    {
        $contactForm = factory(ContactForm::class, 5)->create()->toArray();
        $response = $this->json('GET', 'api/admin/contact-forms/list');
        $response->assertStatus(200);
        foreach ($contactForm as $item) {
            $response->assertJsonFragment([
                'name' => $item['name'],
                'slug' => $item['slug'],
            ]);
        }
    }
}
