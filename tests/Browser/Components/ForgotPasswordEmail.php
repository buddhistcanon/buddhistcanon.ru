<?php
namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class ForgotPasswordEmail
{

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function clickResetPassword() {
        $this->browser
            ->waitFor('#preview-html')
            ->withinFrame('#preview-html', function ($browser) {
                $this->browser->waitForText('Reset Password')
                    ->clickLink('Reset Password');
            });

        $handles = $this->browser->driver->getWindowHandles();
        $this->browser->driver->switchTo()->window($handles[count($handles) - 1]);
        return $this;
    }

}
