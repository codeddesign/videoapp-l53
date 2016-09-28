<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

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
        User::create([
            'name' => 'test user',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'remember_token' => str_random(10),
            'verified_phone' => 1,
            'verified_email' => 1,
        ]);

        $this->visit('/login')
            ->type('test@gmail.com', 'email')
            ->type('123456', 'password')
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
        User::create([
            'name' => 'test user',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'remember_token' => str_random(10),
            'verified_phone' => 0,
            'verified_email' => 1,
        ]);

        $this->visit('/login')
            ->type('test@gmail.com', 'email')
            ->type('123456', 'password')
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
        User::create([
            'name' => 'test user',
            'email' => 'test@gmail.com',
            'password' => '123456',
            'remember_token' => str_random(10),
            'verified_phone' => 1,
            'verified_email' => 0,
        ]);

        $this->visit('/login')
            ->type('test@gmail.com', 'email')
            ->type('123456', 'password')
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
