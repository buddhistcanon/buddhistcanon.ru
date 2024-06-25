<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\ForgotPasswordEmail;
use Tests\Browser\Components\ForgotPasswordForm;
use Tests\Browser\Components\LoginForm;
use Tests\Browser\Components\ResetPasswordForm;
use Tests\Browser\Pages\HomePage;
use Tests\Browser\Pages\MailpitPage;
use Tests\DuskTestCase;

class AdminUsersPageTest extends DuskTestCase
{
    public function testUserCanResetPassword(): void
    {
        try {
            $this->browse(function (Browser $browser) {
                (new LoginForm($browser))
                    ->logIn('user@user.com', '123')
                    ->clickForgotPassword();

                (new ForgotPasswordForm($browser))
                    ->submitResetPasswordForm('user@user.com')
                    ->assertSuccessfullySent();

                (new MailpitPage($browser))
                    ->visit()
                    ->openEmail('Reset Password Notification');

                (new ForgotPasswordEmail($browser))
                    ->clickResetPassword();

                (new ResetPasswordForm($browser))
                    ->resetPassword('user@user.com', '12345678', '12345678');

                (new LoginForm($browser))
                    ->assertPasswordResetMessage()
                    ->logIn('user@user.com', '12345678');

                (new HomePage($browser))
                    ->assertHomePage();

                $this->assertTrue(true);
            });
        } finally {
            $user = User::where('email', 'user@user.com')->first();
            $user->password = Hash::make('123456');
            $user->save();
        }
    }
}
