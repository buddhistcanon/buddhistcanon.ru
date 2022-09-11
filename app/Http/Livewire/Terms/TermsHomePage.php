<?php

namespace App\Http\Livewire\Terms;

use App\Models\Term;
use Livewire\Component;

class TermsHomePage extends Component
{
    /**
     * @var Term[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|mixed
     */
    public $allTerms;

    public function mount()
    {
        $this->allTerms = Term::query()->orderBy('id', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.terms.terms-home-page');
    }
}
