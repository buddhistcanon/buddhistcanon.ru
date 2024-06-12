<?php

namespace Tests\Browser\Admin;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminMenu;
use Tests\Browser\Components\LoginForm;
use Tests\DuskTestCase;

class AdminRoleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     */
    public function testAdminCanAccessUsersPage(): void
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

    public function testRolesAreRenderedCorrectlyOnUsersPage() {
        $user = $this->createUser('editor_all@editor.com');

        try {
            $this->browse(function (Browser $browser) {
                (new LoginForm($browser))->logIn('admin@admin.com', '123456');
                (new AdminMenu($browser))->waitTillLoaded();
                $browser->visit('/admin/users');
                $browser->waitForText('Суперадмин');
                $browser->waitForText('editor_russian');
                $browser->waitForText('editor_russian, editor_english');
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

        $editor_rus_eng = \App\Models\Role::where('name', 'editor_english')->first();
        $user->roles()->attach($editor_rus_eng->id);
        $user->save();

        return $user;
    }
}
