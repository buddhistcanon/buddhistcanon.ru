<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;

class TextResultData extends Data
{
    public function __construct(
        public ?int $contentChunkId,
        public string $html,
        public ?string $urlWithMark = null, // урл абзаца (вместе с mark)
        public ?string $translatorName = null // Сергей SV,
    )
    {    }


}
