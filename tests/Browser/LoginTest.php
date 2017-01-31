<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\Browser\Pages\Login;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test visiting the homepage, clicking on 'login' link
     * and redirecting to the '/login' page.
     *
     * @test
     */
    public function login_page_working()
    {
        $this->browse(function ($browser) {
            $browser->visit(new Login);
        });
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
            'password' => '123123',
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', '123123')
                ->click('@submit')
                ->assertPathIs('/app');
        });
    }

    /**
     * @test
     */
    public function it_redirects_to_phone_verification()
    {
        $user = factory(User::class)->create([
            'password' => '123123',
            'verified_phone' => false,
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', '123123')
                ->click('@submit')
                ->assertPathIs('/verify/phone');
        });
    }

    /**
     * @test
     */
    public function it_redirects_to_email_verification()
    {
        $user = factory(User::class)->create([
            'password' => '123123',
            'verified_email' => false,
        ]);

        $this->browse(function ($browser) use ($user) {
            $browser->visit(new Login)
                ->type('@email', $user->email)
                ->type('@password', '123123')
                ->click('@submit')
                ->assertPathIs('/verify/email');
        });
    }
}
