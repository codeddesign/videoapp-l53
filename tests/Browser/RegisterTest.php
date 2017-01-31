<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Register;
use Tests\DuskTestCase;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function register_page_working()
    {
        $this->browse(function ($browser) {
            $browser->visit(new Register);
        });
    }

    /**
     * @test
     */
    public function signup()
    {
        $this->browse(function ($browser) {
            $browser->visit(new Register)
                ->type('@firstName', 'Rui')
                ->type('@lastName', 'Gomes')
                ->type('@company', 'a3m')
                ->type('@email', 'hello@ruigomes.me')
                ->type('@password', '123123')
                ->type('input[name=password_confirmation]', '123123')
                ->click('@submit')
                ->assertPathIs('/verify/phone');

            $this->assertDatabaseHas('users', [
                'first_name' => 'Rui',
                'last_name'  => 'Gomes',
                'company'    => 'a3m',
                'email'      => 'hello@ruigomes.me',
            ]);
        });
    }
}
