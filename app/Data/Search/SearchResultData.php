<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;

class SearchResultData extends Data
{
    public function __construct(
        public string $text,
        public string $language,
        public string $suttaName,
        public string $url,
    ) {

    }
}
