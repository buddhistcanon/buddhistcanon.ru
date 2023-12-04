<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class SearchResultsData extends Data
{
    public function __construct(
        /** @var SuttaResultData[] */
        public ?DataCollection $suttasResult = null,
        /** @var BookResultData[] */
        public ?DataCollection $booksResult = null
    ) {
    }

    //    public static function fromArray(array $suttasMeiliHits, array $booksMeiliHits)
    //    {
    //
    //    }
}
