<?php
namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class LoginForm
{

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function logIn(string $email, string $password, $pause = false): void
    {
        $this->browser->visit('/')
            ->pause($pause ? 3000 : 0)
            ->waitForText('Вход / Регистрация')
            ->clickLink('Вход / Регистрация')
            ->waitFor('input#email', 10)
            ->type('input#email', $email)
            ->type('input#password', $password)
            ->press('ВХОД');
    }

}
