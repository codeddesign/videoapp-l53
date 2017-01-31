<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class LandingPageTest extends DuskTestCase
{
    /**
     * @test
     *
     * A basic browser test example.
     *
     * @return void
     */
    public function working_landing_page()
    {
        $this->browse(function ($browser) {
            $browser->visit('/')
                    ->assertSee('Ad3');
        });
    }
}
