<?php

namespace App\Tests\Integration\Dashboard;

use App\Testing\TestCase;
use App\User;
use App\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test visiting the homepage, clicking on 'login' link
     * and redirecting to the '/login' page.
     *
     * @test
     */
    public function it_clicks_on_login_and_redirect_to_login_page()
    {
        $this->visit('/')
            ->click('login')
            ->assertResponseOk()
            ->seePageIs('/login');
    }

    /**
     * Test seeing the login page.
     * by clicking on the login link on the homepage.
     *
     * @test
     */
    public function it_shows_login_page()
    {
        $this->visit('/login')
            ->assertResponseOk()
            ->see('REGISTER')
            ->isInstanceOf(\Illuminate\View\View::class);
    }

    /**
     * Test logging in.
     * Both phone and email are verified.
     *
     * @test
     */
    public function it_logs_in_the_user()
    {
        $user = factory(User::class)->create([
            'password' => '123123'
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('123123', 'password')
            ->press('login')
            ->seePageIs('app');
    }

    /**
     * Test logging in.
     * email is verified, phone is not verified.
     *
     * @test
     */
    public function it_shows_verify_phone_page()
    {
        $user = factory(User::class)->create([
            'password' => '123123',
            'verified_phone' => false
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('123123', 'password')
            ->press('login')
            ->seePageIs('/verify/phone');
    }

    /**
     * Test logging in.
     * email is not verified, phone is verified.
     *
     * @test
     */
    public function it_shows_verify_email_page()
    {
        $user = factory(User::class)->create([
            'password' => '123123',
            'verified_email' => false
        ]);

        $this->visit('/login')
            ->type($user->email, 'email')
            ->type('123123', 'password')
            ->press('login')
            ->seePageIs('/verify/email');
    }

    /**
     * Test logging out the user.
     *
     * @test
     */
    public function it_logout_the_user()
    {
        $this->visit('/logout')
            ->dontSeeIsAuthenticated()
            ->seePageIs('/');
    }
}
