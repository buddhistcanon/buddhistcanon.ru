<?php namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CrawlerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $crawlerProject = new \App\Models\CrawlerProject();
        $crawlerProject->name = "theravada.ru";
        $crawlerProject->root_url = "http://theravada.ru";
        $crawlerProject->start_url = '/index.htm';
        $crawlerProject->save();

        $crawlerProject = new \App\Models\CrawlerProject();
        $crawlerProject->name = "theravada.su";
        $crawlerProject->root_url = "https://theravada.su";
        $crawlerProject->start_url = '/';
        $crawlerProject->save();

        $crawlerProject = new \App\Models\CrawlerProject();
        $crawlerProject->name = "tipitaka.theravada.su";
        $crawlerProject->root_url = "https://tipitaka.theravada.su";
        $crawlerProject->start_url = '/';
        $crawlerProject->exclude_patterns = ['?ic=', 'bookmark', 'login'];
        $crawlerProject->save();

    }
}
