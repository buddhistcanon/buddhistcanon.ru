<?php

namespace App\Events;

use App\Models\ContentChunk;
use Illuminate\Foundation\Events\Dispatchable;

class TextAddedEvent
{
    use Dispatchable;

    public string $text;

    public function __construct(String $text)
    {
        $this->text = $text;
    }
}
