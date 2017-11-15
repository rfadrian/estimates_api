<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DiscardEstimateTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();
    }
    public function testDiscardEstimate()
    {
        $estimate = factory('App\Models\Estimate')->create(['state_id' => 1]) ;

        $response = $this->json('POST', 'api/estimate/discard/'.$estimate->id);

        $response->assertStatus(200);
        $response->assertJson(['state_id' => 3]);

    }

    public function testDiscardEstimateDiscarded() {

        $estimate = factory('App\Models\Estimate')->create(['state_id' => 3]);
        $data = [
            'title' => 'Lorem ipsum',
            'description' => 'Lorem ipsum dolor sit amet',
            'category_id' => 1
        ];

        $response = $this->json('POST', 'api/estimate/discard/'.$estimate->id);
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Estimate is already discarded']);

    }
}
