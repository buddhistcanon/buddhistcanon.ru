<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SuttaResultData extends Data
{
    public function __construct(
        public string $name, // mn148
        public ?string $url = null,
        /** @var TextResultData[] */
        public ?DataCollection $textResults = null,
    ) {
    }
}
