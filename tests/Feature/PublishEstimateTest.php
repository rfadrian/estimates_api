<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublishEstimateTest extends TestCase
{

    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();
    }
    public function testPublishEstimate()
    {
        $estimate = factory('App\Models\Estimate')->create(['state_id' => 1]) ;

        $response = $this->json('POST', 'api/estimate/publish/'.$estimate->id);
        $response->assertStatus(200);
    }

    public function testPublishEstimateNotPending()
    {
        $estimate = factory('App\Models\Estimate')->create(['state_id' => 2]);

        $response = $this->json('POST', 'api/estimate/publish/'.$estimate->id);
        $response->assertStatus(400);
    }


    public function testPublishEstimateMissingTitle()
    {
        $estimate = factory('App\Models\Estimate')->create(['title' => null, 'state_id' => 1]);

        $response = $this->json('POST', 'api/estimate/publish/'.$estimate->id);
        $response->assertStatus(400);
    }

    public function testPublishEstimateMissingCategory()
    {
        $estimate = factory('App\Models\Estimate')->create(['category_id' => null, 'state_id' => 1]);

        $response = $this->json('POST', 'api/estimate/publish/'.$estimate->id);
        $response->assertStatus(400);
    }
}
