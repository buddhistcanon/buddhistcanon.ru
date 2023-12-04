<?php

namespace App\Console\Commands;

use App\Crawlers\TheravadaruCrawlObserver;
use App\Crawlers\TheravadaruCrawlProfile;
use App\Crawlers\TipitakaCrawlObserver;
use App\Crawlers\TipitakaCrawlProfile;
use App\Models\CrawlerPage;
use App\Models\CrawlerProject;
use Illuminate\Console\Command;
use Spatie\Crawler\Crawler;
use Spatie\Crawler\CrawlInternalUrls;

class CrawlCommand extends Command
{
    protected $signature = 'lb:crawl {project_name?} {--delete}';

    protected $description = 'Crawl project by name with spatie crawler';

    public function handle()
    {
        $projectName = $this->argument('project_name');
        if (! $projectName) {
            $projectName = 'theravada.ru';
        }
        $project = CrawlerProject::query()->where('name', $projectName)->firstOrFail();
        $project->increment('session');
        $this->info("Start crawl project $project->name with session $project->session");
        dump($project->toArray());

        $crawler = Crawler::create()
            ->acceptNofollowLinks()
            ->ignoreRobots()
            ->setUserAgent('Mozilla/5.0 (X11; Linux x86_64; rv:100.0) Gecko/20100101 Firefox/100.0')
            ->setParseableMimeTypes(['text/html', 'text/plain']);

        switch ($projectName) {
            case 'theravada.ru':
                $crawler
                    ->setConcurrency(1)
                    ->setDelayBetweenRequests(500)
                    ->setCrawlProfile(new CrawlInternalUrls($project->root_url))
                    ->setCrawlProfile(new TheravadaruCrawlProfile())
                    ->setCrawlObserver(new TheravadaruCrawlObserver($project, true))
                    ->startCrawling($project->root_url.$project->start_url);
                break;
            case 'theravada.su':
                $var = '22';
                break;
            case 'tipitaka.theravada.su':
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

        $this->line('Crawl done.');
    }
}
