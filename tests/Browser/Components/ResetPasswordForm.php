<?php
namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class ResetPasswordForm
{

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function resetPassword(string $email, string $password, $password_confirmation)
    {
        $this->browser
            ->waitFor('#email')
            ->type('#email', $email)
            ->type('#password', $password)
            ->type('#password_confirmation', $password_confirmation)
            ->press('УСТАНОВИТЬ ПАРОЛЬ');
        return $this;
    }
}
