<?php

namespace App\Console\Commands;

use App\Crawlers\TipitakaCrawlObserver;
use App\Crawlers\TipitakaCrawlProfile;
use App\Models\CrawlerPage;
use App\Models\CrawlerProject;
use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlInternalUrls;

class CrawlCommand extends Command
{
    protected $signature = 'lb:crawl {project_name?}';

    protected $description = 'Crawl project by name with spatie crawler';

    public function handle()
    {
        $projectName = $this->argument('project_name');
        $projectName = "tipitaka.theravada.su";
        $project = CrawlerProject::query()->where("name", $projectName)->firstOrFail();
        $project->increment("session");
        $this->info("Start crawl project $project->name with session $project->session");

        $crawler = Crawler::create()
            ->acceptNofollowLinks()
            ->ignoreRobots()
            ->setUserAgent('learn-buddhism.ru crawler')
            ->setParseableMimeTypes(['text/html', 'text/plain']);

        switch($projectName){
            case "theravada.ru":
                break;
            case "theravada.su":
                $var = "22";
                break;
            case "tipitaka.theravada.su":
                CrawlerPage::query()->delete();
                $crawler
                    ->setConcurrency(1)
                    ->setDelayBetweenRequests(10)
                    ->setCrawlProfile(new CrawlInternalUrls($project->root_url))
                    ->setCrawlProfile(new TipitakaCrawlProfile())
                    ->setCrawlObserver(new TipitakaCrawlObserver($project, true))
                    ->startCrawling($project->root_url.$project->start_url);
                break;
            default:

        }
    }
}
