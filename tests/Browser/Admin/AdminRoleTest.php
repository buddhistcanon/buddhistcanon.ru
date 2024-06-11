<?php

namespace Tests\Browser\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminMenu;
use Tests\Browser\Components\LoginForm;
use Tests\DuskTestCase;

class AdminRoleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testEditorCanNotAccessAdminUserPage(): void
    {
        $this->browse(function (Browser $browser) {
            (new LoginForm($browser))->logIn('admin@admin.com', '123456');
            (new AdminMenu($browser))
                ->waitTillLoaded()
                ->assertUsersMenuVisible()
                ->clickUsersMenu()
                ->waitTillLoaded();
            // TODO: add users page assertion
            $browser->assertTitle('Фонд Канона Буддизма');
        });
    }


}
