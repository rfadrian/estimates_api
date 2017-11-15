<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditEstimateTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();
    }
    public function testEditEstimate()
    {
        $estimate = factory('App\Models\Estimate')->create(['state_id' => 1]) ;
        $data = [
            'title' => 'Lorem ipsum',
            'description' => 'Lorem ipsum dolor sit amet',
            'category_id' => 1
        ];
        $response = $this->json('POST', 'api/estimate/'.$estimate->id, $data);

        $response->assertStatus(200);

    }

    public function testEditEstimateNotPending() {

        $estimate = factory('App\Models\Estimate')->create(['state_id' => 2]);
        $data = [
            'title' => 'Lorem ipsum',
            'description' => 'Lorem ipsum dolor sit amet',
            'category_id' => 1
        ];

        $response = $this->json('POST', 'api/estimate/'.$estimate->id, $data);
        $response->assertStatus(400);


    }
}
