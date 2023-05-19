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
     *
     * @param  \Psr\Http\Message\UriInterface  $url
     * @return bool
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        if(str_contains($url->getPath(), "Canon") AND str_contains($url->getPath(), ".htm")){
            return true;
        }
        return false;
    }
}
