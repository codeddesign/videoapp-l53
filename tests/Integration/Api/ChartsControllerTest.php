<?php

namespace Integration\Api;

use App\Testing\TestCase;
use App\User;

class ChartsControllerTest extends TestCase
{
    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_fetches_all_charts()
    {
        $this->actingAs($this->user, 'api')
            ->visit('/api/charts/all');

        dd(json_decode($this->response->content()));
    }

}
