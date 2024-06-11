<?php

namespace Tests\Browser\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminMenu;
use Tests\Browser\Components\LoginForm;
use Tests\DuskTestCase;

class EditorRoleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testEditorCanNotAccessAdminUserPage(): void
    {
        $this->browse(function (Browser $browser) {
            (new LoginForm($browser))->logIn('editor_russian@editor.com', '123456');

            $menu = new AdminMenu($browser);
            $menu
                ->waitTillLoaded()
                ->assertUsersMenuMissing();

            $browser->visit($menu->getUsersMenuLink());

            $browser->waitForText('ACCESS DENIED', 10);
            $browser->assertTitle('Forbidden');
        });
    }


}
