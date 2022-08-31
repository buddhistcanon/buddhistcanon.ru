<?php

namespace App\Data\Search;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class BookResultData extends Data
{
    public function __construct(
        public int $bookId,
        public ?string $author = null,
        public string $title,
        public ?string $url = null,
        /** @var TextResultData[] */
        public ?DataCollection $textResults = null,
    )
    {
        $this->author = $author;

    }
}
