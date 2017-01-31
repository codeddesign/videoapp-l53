<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class Register extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/register';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param \Laravel\Dusk\Browser $browser
     *
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@firstName'            => 'input[name=first_name]',
            '@lastName'             => 'input[name=last_name]',
            '@company'              => 'input[name=company]',
            '@email'                => 'input[name=email]',
            '@password'             => 'input[name=password]',
            '@submit'               => '#register',
        ];
    }
}
