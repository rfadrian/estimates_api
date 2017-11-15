<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListEstimateTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();
    }
    public function testListEstimate()
    {
        $data = ['email' => null, 'page' => null];
        $response = $this->json('GET', 'api/estimate', $data);
        $response->assertStatus(200);
    }

    public function testListEstimatePaginated()
    {
        $data = ['email' => null, 'page' => 2];

        $response = $this->json('GET', 'api/estimate', $data);
        $response->assertStatus(200);
    }


    public function testListEstimateFiltered()
    {
        $data = ['email' => 'user@example.com', 'page' => null];

        $user = factory('App\Models\User')->create(['email' => 'user@example.com']);
        $estimate = factory('App\Models\Estimate')->create(['user_id' => $user->id]);

        $response = $this->json('GET', 'api/estimate', $data);
        $response->assertStatus(200);
    }

    public function testLostEstimateNotFoundEmail()
    {
        $data = ['email' => 'notuser@example.com', 'page' => null];

        $response = $this->json('GET', 'api/estimate/', $data);
        $response->assertStatus(200);
        $response->assertJson(['total'=> 0]);

    }
}
