<?php

namespace App\Http\Livewire\Terms;

use App\Models\TermVariant;
use Livewire\Component;

class TermPanel extends Component
{
    public $title;
    public $content;
    public $termId;

    protected $listeners = ['showTerm'];

    public function mount()
    {
        $title = "Поиск термина..";
    }

    public function showTerm($termTitle)
    {
    	$this->title = $termTitle;

    	$term = TermVariant::query()->where("title", $this->title)->first()->term;
    	if($term){
    	    $this->termId = $term->id;
    	    // $this->content = $term->content =
        }
    }

    public function hidePanel()
    {
        $title = "Поиск термина..";
        $content = "";
    }

    public function render()
    {
        return view('livewire.terms.term-panel');
    }
}
