<?php

namespace App\Http\Livewire\Common;

use App\TextParser\TextParser;
use Livewire\Component;
use function view;

class ContentChunk extends Component
{
    public $content;
    public $html;
    public $numColumns;

    public function mount($text, $numColumns)
    {
        $textParser = new TextParser();
        $this->html = $textParser->parse($text, $this->id);
        $this->numColumns = $numColumns;
    }

    public function clickTerm($termTitle = "Термин не найден")
    {
        //$this->clickedTermTitle = $termTitle;
        $this->emit("showTerm", $termTitle);
    }

    public function render()
    {
        return view('livewire.common.content-chunk');
    }
}
