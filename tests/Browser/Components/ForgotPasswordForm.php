<?php
namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class ForgotPasswordForm
{

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function submitResetPasswordForm($email) {
        $this->browser->waitForText('СБРОСИТЬ ПАРОЛЬ')
            ->type('input#email', $email)
            ->press('СБРОСИТЬ ПАРОЛЬ');
        return $this;
    }

    public function assertSuccessfullySent()
    {
        $this->browser->waitForText('Мы отправили вам ссылку сброса пароля на e-mail!');
    }

}
