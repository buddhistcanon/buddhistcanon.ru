<?php

namespace App\Crawlers;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfile;

class TipitakaCrawlProfile extends CrawlProfile
{
    public function __construct()
    {
    }

    /**
     * Determine if the given url should be crawled.
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        if (
            strpos($url->getQuery(), 'ic=') !== false
            or strpos($url->getPath(), 'bookmark') !== false
            or strpos($url->getPath(), 'login') !== false
            or strpos($url->getPath(), 'locale') !== false
            or strpos($url->getPath(), 'term/') !== false
        ) {
            return false;
        }

        return true;
    }
}
