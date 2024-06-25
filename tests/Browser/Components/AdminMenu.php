<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

class AdminMenu
{
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    public function waitTillLoaded()
    {
        $this->browser->waitFor('a[href="/logout"]', 10);
        $this->browser->assertVisible('a[href="/logout"]');
        return $this;
    }

    public function getUsersMenuLink() {
        return '/admin/users';
    }

    public function assertUsersMenuVisible()
    {
        $link = $this->getUsersMenuLink();
        $this->browser->assertVisible("a[href=\"$link\"]");
        return $this;
    }

    public function assertUsersMenuMissing()
    {
        $link = $this->getUsersMenuLink();
        $this->browser->assertMissing("a[href=\"$link\"]");
        return $this;
    }

    public function clickUsersMenu()
    {
        $this->browser->clickLink('Пользователи');
        return $this;
    }

    public function clickGotoSite() {
        $this->browser->waitForLink("На сайт")->clickLink("На сайт");
    }
}
