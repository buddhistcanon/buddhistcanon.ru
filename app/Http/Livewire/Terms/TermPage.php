<?php

namespace App\Http\Livewire\Terms;

use App\Models\Term;
use Illuminate\Http\Request;
use Livewire\Component;

class TermPage extends Component
{
    /**
     * @var Term|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed
     */
    public $term;

    public function mount(Request $request)
    {
        $slug = $request->route()->slug;
        $this->term = Term::query()->where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.terms.term-page');
    }
}
