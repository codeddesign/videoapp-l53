<?php

namespace App\Tests\Integration\Dashboard;

use App\Testing\TestCase;
use App\Testing\DatabaseTransactions;
use Illuminate\View\View;

class HomePageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test visiting the home page.
     *
     * @test
     */
    public function it_shows_the_home_page()
    {
        $this->visit('/')
            ->assertResponseOk()
            ->see('High Impact Ad Solutions')
            ->isInstanceOf(View::class);
    }
}
