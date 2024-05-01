<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;

class TextResultData extends Data
{
    public function __construct(
        public ?int $contentChunkId,
        public string $html,
        public ?string $url = null, // урл абзаца (вместе с mark)
        public ?string $fullUrl = null, // урл с названием сайта и без mark
        public ?string $translation = null // Сергей SV,
    ) {
    }
}
