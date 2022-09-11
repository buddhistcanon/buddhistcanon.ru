<?php

namespace App\Http\Livewire;

use App\Models\EmailSubscribe;
use Livewire\Component;

class PromoEmail extends Component
{
    public $email = '';

    public $success = '';

    public $error = '';

    public function submit_email()
    {
        $this->success = '';
        $this->validate([
            'email' => ['required', 'email'],
        ], [
            'required' => 'Укажите свой email.',
            'email' => 'Проверьте пожалуйста, это не похоже на email.',
        ]);

        $exists = EmailSubscribe::query()->where('email', $this->email)->first();
        if ($exists) {
            $this->success = 'Этот email уже есть в нашей базе.';
        } else {
            $emailSubscribe = new EmailSubscribe();
            $emailSubscribe->email = $this->email;
            $emailSubscribe->save();

            $this->success = 'Отлично, будем держать вас в курсе!';
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('livewire.promo-email');
    }
}
