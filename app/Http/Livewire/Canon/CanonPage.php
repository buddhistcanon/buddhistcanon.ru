<?php

namespace App\Http\Livewire\Canon;

use App\Models\Sutta;
use Illuminate\Support\Collection;
use Livewire\Component;

class CanonPage extends Component
{
    public Collection $suttas;

    public function mount()
    {
    	$this->suttas = Sutta::query()
            ->with("contents")
            ->with("contents.translator")
            ->orderBy("order", "asc")
            ->get();
    }

    public function render()
    {
        return view('livewire.canon.canon-page');
    }
}
