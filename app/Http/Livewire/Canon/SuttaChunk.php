<?php

namespace App\Http\Livewire\Canon;

use App\TextParser\TextParser;
use Livewire\Component;
use function view;

class SuttaChunk extends Component
{
    public $content;
    public $html;
    public $numColumns;
    public $mark;

    public function mount($text, $numColumns, $mark)
    {
        $textParser = new TextParser();
        $this->html = $textParser->parse($text, $this->id);
        $this->numColumns = $numColumns;
        $this->mark = $mark;
    }

    public function clickTerm($termTitle = "Термин не найден")
    {
        //$this->clickedTermTitle = $termTitle;
        $this->emit("showTerm", $termTitle);
    }

    public function render()
    {
        return view('livewire.canon.sutta-chunk');
    }
}
