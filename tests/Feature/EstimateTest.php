<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstimateTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();
    }
    public function testNewEstimate()
    {
        $data = [
            'title' => 'Lorem ipsum',
            'description' => 'Lorem ipsum dolor sit amet',
            'category_id' => 1,
            'user' => [
                'email' => 'user1@example.com',
                'phone' => '34777555',
                'address' => 'calle con nombre, 20'
            ]
        ];
        $response = $this->json('POST', 'api/estimate/', $data);

        $response->assertStatus(200);

    }
    public function testNewEstimateNullables()
    {
        $data = [
            'description' => 'Lorem ipsum dolor sit amet',
            'user' => [
                'email' => 'user1@example.com',
                'phone' => '34777555',
                'address' => 'calle con nombre, 20'
            ]
        ];
        $response = $this->json('POST', 'api/estimate/', $data);

        $response->assertStatus(200);

    }
    public function testNewEstimateFail()
    {
        $data = [
            'title' => 'Lorem ipsum',
            'category_id' => 1,
            'user' => [
                'email' => 'user1@example.com',
                'phone' => '34777555',
                'address' => 'calle con nombre, 20'
            ]
        ];
        $response = $this->json('POST', 'api/estimate/', $data);

        $response->assertStatus(422);
    }
    public function testNewEstimateFailData()
    {
        $data = [
            'title' => 'Lorem ipsum',
            'category_id' => 'elefante',
            'user' => [
                'email' => 'user1@example.com',
                'phone' => '34777555',
                'address' => 'calle con nombre, 20'
            ]
        ];
        $response = $this->json('POST', 'api/estimate/', $data);

        $response->assertStatus(422);
    }
}
