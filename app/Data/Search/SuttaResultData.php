<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SuttaResultData extends Data
{
    public function __construct(
        public int $suttaId,
        public string $name, // mn148
        public ?string $paliTitle = null, // Chachakkasutta
        public ?string $enTitle = null,
        public ?string $ruTitle = null,
        public ?string $url = null,
        public ?string $urlTheravadaRu = null,
        /** @var TextResultData[] */
        public ?DataCollection $textResults = null,
    ) {
    }
}
