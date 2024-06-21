<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class MailpitPage extends Page
{

    public function __construct(Browser $browser) {
        $this->browser = $browser;
    }

    public function url(): string
    {
        return 'http://127.0.0.1:7406/';
    }

    public function visit()
    {
        $this->browser->visit($this->url());
        return $this;
    }

    public function openEmail($title) {
        $this->browser->waitForText($title)
            ->clickLink($title);
        return $this;
    }

}
