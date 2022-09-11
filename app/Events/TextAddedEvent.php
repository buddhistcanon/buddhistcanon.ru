<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class TextAddedEvent
{
    use Dispatchable;

    public string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }
}
