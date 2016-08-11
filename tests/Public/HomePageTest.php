<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomePageTest extends TestCase
{
    /**
     * Test visting the home page.
     *
     * @test
     */
    public function it_shows_the_home_page()
    {
        $this->visit('/')
            ->assertResponseOk()
            ->see('High Impact Ad Solutions')
            ->isInstanceOf(\Illuminate\View\View::class);
    }
}
