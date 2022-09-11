<?php

namespace App\Crawlers;

use App\Models\CrawlerLog;
use App\Models\CrawlerPage;
use App\Models\CrawlerProject;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObserver;

class TipitakaCrawlObserver extends CrawlObserver
{
    /**
     * @var CrawlerProject
     */
    private CrawlerProject $project;

    private bool $isVerbose;

    public function __construct(CrawlerProject $project, $isVerbose = false)
    {
        $this->project = $project;
        $this->isVerbose = $isVerbose;
    }

    /**
     * Called when the crawler will crawl the url.
     *
     * @param  \Psr\Http\Message\UriInterface  $url
     */
    public function willCrawl(UriInterface $url)
    {
        $fetchedUrl = $this->urlString($url);
        if ($this->isVerbose) {
            echo "$fetchedUrl .. ";
        }
    }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null)
    {
        $html = $response->getBody();
        $html = preg_replace("/<input.*?type=\"hidden\".*?\/>/", '', $html);
        $status = $response->getStatusCode();
        $hash = md5($html);

        $fetchedUrl = $this->urlString($url);
        if ($this->isVerbose) {
            echo 'url loaded . ';
        }

        $crawlerPage = CrawlerPage::withTrashed()->where('url', $fetchedUrl)->first();

        // Новая страница
        if (! $crawlerPage) {
            if ($this->isVerbose) {
                echo 'this is new page';
            }
            $crawlerPage = new CrawlerPage();
            $crawlerPage->project_id = $this->project->id;
            $crawlerPage->url = $fetchedUrl;
            $crawlerPage->status = $status;
            $crawlerPage->content = $html;
            $crawlerPage->content_hash = $hash;
            $crawlerPage->hashed_at = now();
            $crawlerPage->session = $this->project->session;
            $crawlerPage->checked_at = now();
        } else {
            // Страница обновлена
            if ($crawlerPage and $crawlerPage->content_hash != $hash) {
                if ($this->isVerbose) {
                    echo 'PAGE UPDATED ';
                }

                $crawlerPage->old_content = $crawlerPage->content;

                $crawlerPage->url = $fetchedUrl;
                $crawlerPage->status = $status;
                $crawlerPage->content = $html;
                $crawlerPage->content_hash = $hash;
                $crawlerPage->hashed_at = now();
                $crawlerPage->session = $this->project->session;
                $crawlerPage->checked_at = now();

                CrawlerLog::create([
                    'project_id' => $this->project->id,
                    'url' => $fetchedUrl,
                    'new_content' => $crawlerPage->content,
                    'old_content' => $crawlerPage->old_content,
                ]);
            }

            // Страница не изменилась
            elseif ($crawlerPage and $crawlerPage->content_hash == $hash) {
                if ($this->isVerbose) {
                    echo 'page exists   ';
                }
                $crawlerPage->session = $this->project->session;
                $crawlerPage->checked_at = now();
            }
        }

        $crawlerPage->save();
        if ($this->isVerbose) {
            echo "\n";
        }
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null)
    {
        $fetchedUrl = $url->getPath().'?'.$url->getQuery();
        echo "Fetch error $fetchedUrl : ".$requestException->getMessage();
        $crawlerPage = CrawlerPage::where('url', $fetchedUrl)->first();
        if ($crawlerPage) {
            $crawlerPage->delete();
        }
    }

    /**
     * Called when the crawl has ended.
     */
    public function finishedCrawling()
    {
        $this->project->crawled_at = now();
        $this->project->save();
    }

    public function urlString(UriInterface $uri)
    {
        $urlString = $uri->getPath();
        if ($uri->getQuery()) {
            $urlString .= '?'.$uri->getQuery();
        }

        return $urlString;
    }
}
