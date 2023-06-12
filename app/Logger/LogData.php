<?php

namespace App\Logger;
use Spatie\LaravelData\Data;
class LogData extends Data
{
    public function __construct(
        public string $action,
        public int|null $userId = null,
        public ?int $suttaId = null,
        public ?int $termId = null,
        public ?int $contentId = null,
        public ?int $chunkId = null,
        public ?array $storage = null,
        public ?array $before = null,
        public ?array $after = null
    )
    {

    }

}
