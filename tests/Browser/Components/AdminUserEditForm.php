<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;


class AdminUserEditForm
{
    public function __construct(Browser $browser, $userId) {
        $this->browser = $browser;
        $this->userId = $userId;
    }

    public function open() {
        $this->browser->whenAvailable($this->_userRowSelector(), function ($browser) {
            $browser->press('Редактировать');
            $browser->waitForText('Назначить роли:');
        });
        return $this;
    }

    public function selectSuperadmin(bool $isSuperadmin = true)
    {
        $this->browser->whenAvailable('#roles-form', function ($form) use ($isSuperadmin) {
            $form->select('is_superadmin', $isSuperadmin ? '1' : '0');
        });
        return $this;
    }

    public function clickRoleOption(string $role)
    {
        $this->browser->whenAvailable('#roles-form', function ($form) use ($role) {
            $form->elements("option[class=$role]")[0]->click();
        });
        return $this;
    }

    public function save() {
        $this->browser->press('Сохранить')->waitUntilMissingText('Сохранить');
        return $this;
    }

    public function assertRoleString(string $param)
    {
        $this->browser->whenAvailable($this->_userRolesColSelector(), function ($browser) use ($param) {
            $browser->assertSee($param);
        });
        return $this;
    }

    public function assertMissingRoleString(string $string)
    {
        $this->browser->whenAvailable($this->_userRolesColSelector(), function ($browser) use ($string) {
            $browser->waitUntilMissingText($string);
        });
        return $this;
    }

    private function _userRowSelector() {
        return '#user-tr-' . $this->userId;
    }

    private function _userRolesColSelector()
    {
        return '#roles-user-' . $this->userId;
    }
}
