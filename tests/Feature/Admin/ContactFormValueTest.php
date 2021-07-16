<?php

namespace VCComponent\Laravel\ConfigContact\Test\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\ConfigContact\Entites\ContactForm;
use VCComponent\Laravel\ConfigContact\Entites\ContactFormValue;
use VCComponent\Laravel\ConfigContact\Test\TestCase;

class ContactFormValueTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_get_contact_form_list_paginate_admin()
    {
        $contactFormValues = factory(ContactFormValue::class, 5)->create()->toArray();
        $listIds = array_column($contactFormValues, 'id');
        array_multisort($listIds, SORT_DESC, $contactFormValues);
        $response = $this->json('GET', 'api/admin/contact-form-value');
        $response->assertStatus(200);
        $response->assertJson(['data' => $contactFormValues]);
    }

    /**
     * @test
     */
    public function should_not_get_contact_form_value_item_not_exists_admin()
    {
        factory(ContactFormValue::class)->create();
        $response = $this->json('GET', 'api/admin/contact-form-value/2');
        $response->assertStatus(500);
        $response->assertJson(["message" => "Server Error"]);
    }
    /**
     * @test
     */
    public function should_get_contact_form_value_item_admin()
    {
        $contactFormValue = factory(ContactFormValue::class)->create();
        $response = $this->json('GET', 'api/admin/contact-form-value/' . $contactFormValue->id);
        $response->assertStatus(200);
        $response->assertJson([
            'contact_form_id' => $contactFormValue->contact_form_id,
            'payload' => $contactFormValue->payload,
            'status' => $contactFormValue->status,
        ]);
    }
    /**
     * @test
     */
    public function should_not_update_contact_form_value_not_exists_admin()
    {
        $contactFormValue = factory(ContactFormValue::class)->create();
        $contactFormValue->payload = ['email' => 'test@gmail.com'];
        $data = $contactFormValue->toArray();
        $response = $this->json('PUT', 'api/admin/contact-form-value/2', $data);
        $response->assertStatus(500);
        $response->assertJson(["message" => "Server Error"]);

    }
    /**
     * @test
     */
    public function should_update_contact_form_value_admin()
    {
        $contactFormValue = factory(ContactFormValue::class)->create();
        unset($contactFormValue['updated_at']);
        unset($contactFormValue['created_at']);
        $id = $contactFormValue->id;
        $contactFormValue->payload = ['email' => 'test@gmail.com'];
        $data = $contactFormValue->toArray();
        $response = $this->json('PUT', 'api/admin/contact-form-value/' . $id, $data);
        $response->assertStatus(200);
        $data['payload'] = json_encode($data['payload']);
        $response->assertJson([
            'payload' => $data['payload'],
        ]);
        $this->assertDatabaseHas('contact_form_values', $data);
    }
    /**
     * @test
     */
    public function should_soft_delete_contact_form_value_admin()
    {
        $contactFormValue = factory(ContactFormValue::class)->create()->toArray();
        unset($contactFormValue['updated_at']);
        unset($contactFormValue['created_at']);
        $this->assertDatabaseHas('contact_form_values', $contactFormValue);
        $response = $this->json('DELETE', 'api/admin/contact-form-value/' . $contactFormValue['id']);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDeleted('contact_form_values', $contactFormValue);
    }
    /**
     * @test
     */
    public function should_not_update_status_contact_form_input_required_admin()
    {
        $contactFormValue = factory(ContactFormValue::class)->create()->toArray();
        unset($contactFormValue['updated_at']);
        unset($contactFormValue['created_at']);
        $this->assertDatabaseHas('contact_form_values', $contactFormValue);
        $data = ['status' => ''];
        $response = $this->json('PUT', 'api/admin/contact-form-value/' . $contactFormValue['id'] . '/status', $data);
        $response->assertStatus(500);
        $response->assertJson(["message" => "Server Error"]);

    }
    /**
     * @test
     */
    public function should_update_status_contact_form_input_admin()
    {
        $contactFormValue = factory(ContactFormValue::class)->create()->toArray();
        unset($contactFormValue['updated_at']);
        unset($contactFormValue['created_at']);
        $this->assertDatabaseHas('contact_form_values', $contactFormValue);
        $data = ['status' => 2];
        $response = $this->json('PUT', 'api/admin/contact-form-value/' . $contactFormValue['id'] . '/status', $data);
        $response->assertStatus(200);
        $response = $this->json('GET', 'api/admin/contact-form-value/' . $contactFormValue['id']);
        $response->assertJson(['status' => 2]);
    }

    /**
     * @test
     */
    public function should_get_contact_form_list_with_status_admin()
    {
        $contactForm = factory(ContactForm::class)->create();
        $contactFormValue = factory(ContactFormValue::class, 5)->create(['status' => 2]);
        factory(ContactFormValue::class, 5)->create(['status' => 1]);
        $contactFormValueMiss = factory(ContactFormValue::class)->create(['contact_form_id' => 2]);
        $contactFormValue = $contactFormValue->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();
        $response = $this->json('GET', 'api/admin/contact-form/' . $contactForm->id . '/contact-form-value?status=2');
        $response->assertStatus(200);
        foreach ($contactFormValue as $item) {
            $response->assertJsonFragment([
                'payload' => $item['payload'],
                'status' => "2",
            ]);
        }
        $response->assertJsonMissing([
            'payload' => $contactFormValueMiss->payload,
            'status' => "1",
        ]);

    }
    /**
     * @test
     */
    public function should_get_contact_form_list_admin()
    {
        $contactForm = factory(ContactForm::class)->create();
        $contactFormValue = factory(ContactFormValue::class, 5)->create();
        $contactFormValueMiss = factory(ContactFormValue::class)->create(['contact_form_id' => 2]);
        $contactFormValue = $contactFormValue->map(function ($e) {
            unset($e['updated_at']);
            unset($e['created_at']);
            return $e;
        })->toArray();
        $response = $this->json('GET', 'api/admin/contact-form/' . $contactForm->id . '/contact-form-value');
        $response->assertStatus(200);
        foreach ($contactFormValue as $item) {
            $response->assertJsonFragment([
                'payload' => $item['payload'],
            ]);
        }
        $response->assertJsonMissing([
            'payload' => $contactFormValueMiss->payload,
        ]);

    }
}
