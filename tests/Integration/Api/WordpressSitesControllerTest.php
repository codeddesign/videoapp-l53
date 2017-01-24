<?php

namespace Integration\Api;

use App\Models\Website;
use App\Testing\DatabaseTransactions;
use App\Testing\TestCase;
use App\Models\User;

class WordpressSitesControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;
    protected $sites;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->sites = factory(Website::class, 3)->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_fetches_all_wordpress_sites()
    {
        $this->actingAs($this->user, 'api')
            ->visit('/api/wordpress')
            ->seeJsonStructure([
                'data',
            ]);

        $response = json_decode($this->response->content());

        $this->assertEquals(3, count($response->data));
    }
}
