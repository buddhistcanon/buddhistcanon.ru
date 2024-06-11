<?php
namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class LoginForm
{

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function logIn(string $email, string $password): void
    {
        $this->browser->visit('/')
            ->clickLink('Вход / Регистрация')
            ->waitFor('input#email', 10)
            ->type('input#email', $email)
            ->type('input#password', $password)
            ->press('ВХОД');
    }

}
