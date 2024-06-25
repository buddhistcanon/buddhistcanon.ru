<?php

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminMenu;
use Tests\Browser\Components\LoginForm;
use Tests\DuskTestCase;

class UserMenuTest extends DuskTestCase
{
    const ADMIN_AREA_TITLE = 'Admin area';

    public function testUserCannotSeeAdminAreaLink(): void
    {
        $this->browse(function (Browser $browser) {
            (new LoginForm($browser))
                ->logIn('user@user.com', '123456');

            $browser->waitFor('#headlessui-menu-button-5');
            $browser->click('#headlessui-menu-button-5');
            $browser->assertSeeLink("Выход");
            $browser->assertDontSeeLink(self::ADMIN_AREA_TITLE);

            $this->assertTrue(true);
        });
    }

    public function testEditorCanSeeAdminAreaLink(): void
    {
        $this->browse(function (Browser $browser) {
            (new LoginForm($browser))
                ->logIn('editor_russian@editor.com', '123456');

            (new AdminMenu($browser))
                ->waitTillLoaded()
                ->clickGotoSite();

            $browser->waitFor('#headlessui-menu-button-5');
            $browser->click('#headlessui-menu-button-5');
            $browser->assertSeeLink("Выход");
            $browser->assertSeeLink(self::ADMIN_AREA_TITLE);

            $this->assertTrue(true);
        });
    }

    public function testAdminSeeAdminAreaLink(): void
    {
        $this->browse(function (Browser $browser) {
            (new LoginForm($browser))
                ->logIn('admin@admin.com', '123456');

            (new AdminMenu($browser))
                ->waitTillLoaded()
                ->clickGotoSite();

            $browser->waitFor('#headlessui-menu-button-5');
            $browser->click('#headlessui-menu-button-5');
            $browser->assertSeeLink("Выход");
            $browser->assertSeeLink(self::ADMIN_AREA_TITLE);

            $this->assertTrue(true);
        });
    }
}


