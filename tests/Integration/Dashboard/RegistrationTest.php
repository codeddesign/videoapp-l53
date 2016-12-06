<?php

namespace App\Tests\Integration\Dashboard;

use App\Testing\DatabaseTransactions;
use App\Testing\TestCase;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test registering a user.
     *
     * @test
     */
    public function it_register_a_new_user()
    {
        // register a user.
        $this->visit('/register')
            ->type('fname', 'first_name')
            ->type('lname', 'last_name')
            ->type('a3m', 'company')
            ->type('hello@ruigomes.me', 'email')
            ->type('123123', 'password')
            ->type('123123', 'password_confirmation')
            ->press('register');

        // check if the data has been persisted in the db.
        $this->seeInDatabase('users', [
            'first_name'  => 'fname',
            'company'  => 'a3m',
            'email' => 'hello@ruigomes.me',
        ]);

        // check if user is authenticated.
        $this->isAuthenticated();

        // check if the user is redirected to the phone verification page.
        $this->seePageIs('/verify/phone');
    }
}
