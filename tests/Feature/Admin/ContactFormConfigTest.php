<?php

namespace VCComponent\Laravel\ConfigContact\Test\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use VCComponent\Laravel\ConfigContact\Test\TestCase;

class ContactFormConfigTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function should_get_contact_form_pages_admin()
    {
        $check = [
            'contact' => 'Contact',
            'service' => 'servicePage',
        ];
        $response = $this->json('GET', 'api/admin/get-page-list');
        $response->assertStatus(200);
        $response->assertJson($check);
    }
    /**
     * @test
     */

    public function should_get_contact_form_position_admin()
    {
        $check = [
            "position-1" => "On the left",
            "position-2" => "On the right",
        ];
        $response = $this->json('GET', 'api/admin/get-position-list/contact');
        $response->assertStatus(200);
        $response->assertJson($check);
    }

}
