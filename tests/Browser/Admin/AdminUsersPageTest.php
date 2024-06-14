<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminMenu;
use Tests\Browser\Components\AdminUserEditForm;
use Tests\Browser\Components\LoginForm;
use Tests\DuskTestCase;

class AdminUsersPageTest extends DuskTestCase
{
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

    public function testAdminCanAccessUsersPage(): void
    {
        $this->browse(function (Browser $browser) {
            (new LoginForm($browser))->logIn('admin@admin.com', '123456');
            (new AdminMenu($browser))
                ->waitTillLoaded()
                ->assertUsersMenuVisible()
                ->clickUsersMenu();
            $browser->waitForText('editor_russian@editor.com');
        });
    }

    public function testRolesAreRenderedCorrectlyOnUsersPage() {
        $user = $this->createUser('editor_all@editor.com');

        try {
            $this->browse(function (Browser $browser) {
                (new LoginForm($browser))->logIn('admin@admin.com', '123456');
                (new AdminMenu($browser))->waitTillLoaded();
                $browser->visit('/admin/users');
                $browser->waitForText('Суперадмин');
                $browser->waitForText('editor_russian');
                $browser->waitForText('editor_english, editor_russian');
                $browser->waitForText('editor_all@editor.com');
            });
        } finally {
            $user->delete();
        }
    }

    public function testPagination() {
        $users = [];
        for ($i = 0; $i < 30; $i++) {
            $padded = str_pad($i, 2, '0', STR_PAD_LEFT);
            $users[] = $this->createUser("aaa$padded@bar.com");
        }
        try {
            $this->browse(function (Browser $browser) {
                (new LoginForm($browser))->logIn('admin@admin.com', '123456');
                (new AdminMenu($browser))->waitTillLoaded();
                $browser->visit('/admin/users')
                    ->waitForText('aaa19@bar.com')
                    ->assertSee('aaa19@bar.com')
                    ->assertDontSee('aaa20@bar.com')
                    ->clickLink('2')
                    ->waitForText('aaa20@bar.com')
                    ->assertSee('aaa29@bar.com');
            });
        } finally {
            for ($i = 0; $i < 30; $i++) {
                $users[$i]->delete();
            }
        }
    }

    public function testEditRole() {
        try {
            $this->browse(function (Browser $browser) {
                (new LoginForm($browser))->logIn('admin@admin.com', '123456');
                (new AdminMenu($browser))->waitTillLoaded();
                $browser->visit('/admin/users')
                    ->waitForText('editor_russian@editor.com')
                    ->assertSee('Редактировать');

                $this->_testEditRoleSuperadmin($browser);
                $this->_testEditRoleStripAllRoles($browser);
                $this->_testEditRoleAssignMultipleRoles($browser);
            });
        } finally {
            $user = User::whereEmail('editor_russian@editor.com')->first();
            $user->is_superadmin = 0;
            $editor_rus_role = \App\Models\Role::where('name', 'editor_russian')->first();
            $user->roles()->detach();
            $user->roles()->attach($editor_rus_role->id);
            $user->save();
        }
    }

    private function createUser($email) {
        printf("Creating user with email %s\n", $email);
        $user = new \App\Models\User();
        $user->nickname = 'editor_all';
        $user->email = $email;
        $user->password = Hash::make('123456');
        $user->email_verified_at = Carbon::now();
        $user->is_superadmin = 0;
        $user->save();

        $editor_rus_role = \App\Models\Role::where('name', 'editor_russian')->first();
        $user->roles()->attach($editor_rus_role->id);
        $user->save();

        $editor_eng_role = \App\Models\Role::where('name', 'editor_english')->first();
        $user->roles()->attach($editor_eng_role->id);
        $user->save();

        return $user;
    }

    private function _testEditRoleSuperadmin(Browser $browser)
    {
        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 0);

        (new AdminUserEditForm($browser, $user->id))
            ->assertMissingRoleString('Суперадмин')
            ->open()
            ->selectSuperadmin()
            ->save()
            ->assertRoleString('Суперадмин');

        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 1);
    }

    private function _testEditRoleStripAllRoles(Browser $browser)
    {
        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 1);
        $this->assertEquals(count($user->roles()->allRelatedIds()), 1);

        $form = (new AdminUserEditForm($browser, $user->id));
        $form->open();
        $form->selectSuperadmin(false);
        $form->clickRoleOption('editor_russian');
        $form->save();
        $form->assertMissingRoleString('editor_russian');
        $form->assertMissingRoleString('Суперадмин');

        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 0);
        $this->assertEquals(count($user->roles()->allRelatedIds()), 0);
    }

    private function _testEditRoleAssignMultipleRoles(Browser $browser)
    {
        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals(count($user->roles()->allRelatedIds()), 0);

        (new AdminUserEditForm($browser, $user->id))
            ->open()
            ->selectSuperadmin()
            ->clickRoleOption('editor_russian')
            ->clickRoleOption('editor_english')
            ->save()
            ->assertRoleString('editor_russian')
            ->assertRoleString('editor_english');

        $user = User::whereEmail('editor_russian@editor.com')->first();
        $userRoles = $user->roles()->pluck('name')->all();
        sort($userRoles);
        $this->assertEquals($userRoles,
            ['editor_english', 'editor_russian']
        );
    }
}
