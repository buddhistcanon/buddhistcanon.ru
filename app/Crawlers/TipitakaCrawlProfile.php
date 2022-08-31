<?php namespace App\Crawlers;

use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfile;

class TipitakaCrawlProfile extends CrawlProfile
{
    public function __construct()
    {

    }

    /**
     * Determine if the given url should be crawled.
     *
     * @param \Psr\Http\Message\UriInterface $url
     *
     * @return bool
     */
    public function shouldCrawl(UriInterface $url): bool
    {
        if(
            strpos($url->getQuery(), 'ic=') !== false
            OR strpos($url->getPath(), 'bookmark') !== false
            OR strpos($url->getPath(), 'login') !== false
            OR strpos($url->getPath(), 'locale') !== false
            OR strpos($url->getPath(), 'term/') !== false
        ){
            return false;
        }
        return true;
    }
}
