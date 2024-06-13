<?php

namespace Tests\Browser\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminMenu;
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

    private function clickEdit($browser, int $editButtonIndex)
    {
        $buttons = $browser->elements('button');
        $cnt = 0;
        foreach ($buttons as $button) {
            if ($button->getText() === 'Редактировать') {
                $cnt ++;
                if ($editButtonIndex == $cnt) {
                    $button->click();
                    return;
                }
            }
        }
        throw new \Exception("Edit button not found");
    }

    private function _testEditRoleSuperadmin(Browser $browser)
    {
        $this->clickEdit($browser, 2);

        $browser->whenAvailable('#roles-form', function ($form) {
            $form->select('is_superadmin', '1');
        })->press('Сохранить')->waitForText('Суперадмин editor_russian');

        $user = User::whereEmail('editor_russian@editor.com')->first();

        $this->assertEquals($user->is_superadmin, 1);
    }

    private function _testEditRoleStripAllRoles(Browser $browser)
    {
        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 1);
        $this->assertEquals(count($user->roles()->allRelatedIds()), 1);

        $this->clickEdit($browser, 2);

        $browser->whenAvailable('#roles-form', function ($form) {
            $form->select('is_superadmin', '0');
            $form->elements('option[class=editor_russian]')[0]->click();
        })->press('Сохранить')->waitUntilMissingText('Сохранить');

        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 0);
        $this->assertEquals(count($user->roles()->allRelatedIds()), 0);

        $browser->within("#roles-user-{$user->id}", function ($browser) {
            $browser->waitUntilMissingText('editor_russian');
            $browser->waitUntilMissingText('Суперадмин');
        });
    }

    private function _testEditRoleAssignMultipleRoles(Browser $browser)
    {
        $user = User::whereEmail('editor_russian@editor.com')->first();
        $this->assertEquals($user->is_superadmin, 0);
        $this->assertEquals(count($user->roles()->allRelatedIds()), 0);

        $this->clickEdit($browser, 2);

        $browser->whenAvailable('#roles-form', function ($form) {
            $form->elements('option[class=editor_russian]')[0]->click();
            $form->elements('option[class=editor_english]')[0]->click();
        })->press('Сохранить')->waitUntilMissingText('Сохранить');

        $user = User::whereEmail('editor_russian@editor.com')->first();
        $userRoles = $user->roles()->pluck('name')->all();
        sort($userRoles);
        $this->assertEquals($userRoles,
            ['editor_english', 'editor_russian']
        );

        $browser->within("#roles-user-{$user->id}", function ($browser) {
            $browser->assertSee('editor_english, editor_russian');
        });
    }
}
