<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class AdminUsersPage extends Page
{

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function url() {
        return '/admin/users';
    }

    public function navigate() {
        $this->browser->visit($this->url());
        return $this;
    }

    public function assertPageLoadedAndAllowed() {
        $this->assertVisible('a[href="/admin/users"]');
    }
}
