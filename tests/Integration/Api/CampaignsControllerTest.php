<?php

namespace Integration\Api;

use App\Models\Campaign;
use App\Testing\TestCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CampaignsController extends TestCase
{
    protected $user;

    /**
     * @var Collection
     */
    protected $campaigns;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->campaigns = factory(Campaign::class, 3)->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_responds_with_a_paginated_list_of_campaigns()
    {
        $this->actingAs($this->user, 'api')
            ->visit('/api/campaigns')
            ->seeJsonStructure([
                'data',
                'meta' => [
                    'pagination' => [
                        'current_page',
                        'per_page',
                        'count',
                        'total_pages',
                        'total',
                    ],
                ],
            ]);

        $response = json_decode($this->response->content());

        $this->assertEquals(3, count($response->data));
        $this->assertEquals(3, $response->meta->pagination->total);
        $this->assertEquals(1, $response->meta->pagination->current_page);
    }

    /** @test */
    public function it_responds_with_a_single_campaign()
    {
        $campaign = $this->campaigns->random();

        $this->actingAs($this->user, 'api')
            ->visit("/api/campaigns/{$campaign->id}")
            ->seeJsonStructure([
                'data',
            ]);

        $response = json_decode($this->response->content());

        $this->assertEquals($campaign->id, $response->data->id);
    }
}
