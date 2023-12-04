<?php

namespace App\Crawlers;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfile;

class TheravadaruCrawlProfile extends CrawlProfile
{
    public function __construct()
    {
    }

    /**
     * Determine if the given url should be crawled.
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        if (str_contains($url->getPath(), 'Canon') and str_contains($url->getPath(), '.htm')) {
            return true;
        }

        return false;
    }
}
