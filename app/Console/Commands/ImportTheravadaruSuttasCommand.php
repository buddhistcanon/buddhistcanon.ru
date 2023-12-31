<?php

namespace App\Console\Commands;

use App\Data\SuttaNameData;
use App\Models\Content;
use App\Models\People;
use App\Models\Sutta;
use App\Models\TheravadaruSutta;
use App\Services\ContentService;
use App\Services\ImportSuttaFromTheravadaRuService;
use DiDom\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTheravadaruSuttasCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lb:import_theravadaru_suttas {category} {--rebuild} {--debug}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import exists suttas from theravada.ru';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $isRebuild = $this->option('rebuild');
        $isDebug = $this->option('debug');
        $category = $this->argument('category');

        $peopleSV = People::where('nickname', 'SV')->firstOrFail();

        if ($isRebuild) {
            $this->info("Deleteing RU content from $category suttas");
            $suttas = Sutta::query()
                ->where('category', $category)
                ->get();
            $numDeleted = 0;
            foreach ($suttas as $sutta) {
                $contents = $sutta->contents()->where('lang', 'ru')->get();
                foreach ($contents as $content) {
                    $content->chunks()->delete();
                    $content->delete();
                    $numDeleted++;
                }
            }
            $this->info("Deleted $numDeleted contents");
        }

        $domainUrl = 'http://theravada.ru';
        $suttasPath = '/Teaching/Canon/Suttanta';
        $baseUrl = $domainUrl.$suttasPath;
        $document = new Document($baseUrl.'/all-suttas-list.htm', true);
        $area = $document->find('i > a');
        $theravadaRuUrls = [];
        foreach ($area as $node) {
            $url = $node->getAttribute('href');
            if (strpos($url, 'Texts') === false) {
                continue;
            }
            $url = \Str::start($url, '/');

            if (! str_contains($url, '/'.strtolower($category))) {
                continue;
            }
            //$this->line("$url");
            //if(!str_contains($url, "sn25_1-")) continue;

            preg_match("/\/(mn|an|sn|dn|kn)\d/m", $url, $match);
            if(isset($match[1])){
                $category = $match[1];
            }else{
                continue;
            }

            $fullUrl = $baseUrl.$url;
            $theravadaRuUrls[] = $fullUrl;
        }
        //        dd($theravadaRuUrls);

        $theravadaUrlsBySutta = [];
        foreach ($theravadaRuUrls as $url) {
            $lowerCategory = strtolower($category);
            preg_match("/Texts\/$lowerCategory(\d+)_(.*?)-[a-z]/m", $url, $match);
            $order = $match[1];
            $suborder = $match[2];
            echo "$url : $order $suborder"."\n";
            $theravadaUrlsBySutta[strtoupper($category)."$order.$suborder"] = $url;
        }

        $suttas = Sutta::query()
            ->where('category', $category)
            ->get();
        foreach ($suttas as $sutta) {

            if (str_contains($sutta->suborder, '-')) {
                [$start, $end] = explode('-', $sutta->suborder);
            } else {
                $start = $sutta->suborder;
                $end = $sutta->suborder;
            }
            $theravadaRuSutta = null;
            for ($i = 0; $i <= $end - $start; $i++) {
                $name = strtoupper($sutta->category.$sutta->order.'.'.($i + $start));
                $this->line($name." (i=$i)");
                if (isset($theravadaUrlsBySutta[$name])) {

                    // Берём сутту из кэша
                    //$theravadaRuSutta = TheravadaruSutta::query()->where("name", $name)->first();

                    // скачиваем сутту с theravada.ru
                    $url = $theravadaUrlsBySutta[$name];
                    preg_match("/\/(mn|an|sn|dn|kn)\d/m", $url, $match);
                    $category = $match[1];
                    $fullUrl = $url;
                    $this->line("download and parse $fullUrl ..");
                    $import = new ImportSuttaFromTheravadaRuService($isDebug);
                    $import->fetchHtml($fullUrl);

                    if ($i == 0) {
                        $theravadaRuSutta = new TheravadaruSutta();
                        $theravadaRuSutta->category_name = $category;
                        $theravadaRuSutta->order = $sutta->order;
                        $theravadaRuSutta->suborder = $sutta->suborder;
                        $theravadaRuSutta->name = $sutta->name;
                        $theravadaRuSutta->url = $fullUrl;
                        $theravadaRuSutta->copyright = $import->copyright();
                        if (trim($theravadaRuSutta->copyright) == '') {
                            $this->error('empty copyright');
                        }
                        $theravadaRuSutta->content = '';
                        $theravadaRuSutta->original_html = '';
                    }

                    if($theravadaRuSutta){
                        $theravadaRuSutta->content .= "\n\n".$import->content();
                        $theravadaRuSutta->original_html .= "\n\n".$import->original_html();
                        $theravadaRuSutta->need_attention = $import->is_need_attention();
                        if ($theravadaRuSutta->need_attention) {
                            $this->error('need attention');
                        }
                        $this->info($theravadaRuSutta->displayIndexName().' '.$theravadaRuSutta->name);
                        $theravadaRuSutta->save();
                    }else{
                        $this->error("Not found first sutta of $name (period $start - $end,  i=$i)");
                    }


                } else {
//                    $this->error("sutta $name not found in theravadaUrlsBySutta");
                }
            }

            // Сохранение в виде контента к сутте
            if($theravadaRuSutta){
                $suttaNameData = new SuttaNameData($theravadaRuSutta->category_name, $theravadaRuSutta->order, $theravadaRuSutta->suborder);
                $sutta = Sutta::query()
                    ->bySuttaName($suttaNameData)
                    ->first();
                if (! $sutta) {
                    $this->error("Pali original of {$suttaNameData->name()} missing, adding SV translation is impossible");
                } else {
                    $titleArray = explode(':', $theravadaRuSutta->name);
                    if (count($titleArray) == 2) {
                        [$title, $subtitle] = $titleArray;
                    } else {
                        $title = $theravadaRuSutta->name;
                        $subtitle = '';
                    }

                    /** @var Content $content */
                    $content = $sutta->contents()->make();
                    $content->title = $theravadaRuSutta->name;
                    $content->lang = 'ru';
                    $content->is_original = 0;
                    $content->is_synced = 0;
                    $content->translator_id = $peopleSV->id;
                    $content->link_url = $fullUrl;
                    $content->description = str_replace("\n", '', $import->copyright());
                    $content->short_description = 'Перевод с английского перевода Бхикку Бодхи на русский, Сергей SV';
                    $content->save();
                    $this->line('Content SV saved.');

                    $sutta->title_transcribe_ru = trim($title);
                    $sutta->title_translate_ru = trim($subtitle);
                    $sutta->save();

                    $contentService = new ContentService($content);
                    $arrayContent = explode("\n\n", $theravadaRuSutta->content);
                    $contentOrder = 10;
                    $numChunks = 0;
                    foreach ($arrayContent as $chunk) {
                        $contentService->addChunk($chunk, $contentOrder, null);
                        $contentOrder += 10;
                        $numChunks++;
                    }
                    $this->line("Chunks SV added: $numChunks");

                    $contentService->addExternalSource($fullUrl);
                    $this->line('External source added.');
                    $contentService->setMain();
                    $this->line('Marked as main.');
                }
            }

            if ($isDebug) {
                return;
            }
        }

    }

    /*
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle_old()
    {
        //exit("Please check code");
        $isRebuild = $this->option('rebuild');
        $isDebug = $this->option('debug');
        $category = $this->argument('category');

        $peopleSV = People::where('nickname', 'SV')->firstOrFail();

        if ($isRebuild) {
            $this->info("Delete $category suttas");
            $suttas = DB::query()
                ->from('suttas')
                ->where('category', $category)
                ->get();
            $numDeleted = 0;
            foreach ($suttas as $sutta) {
                foreach ($sutta->contents() as $content) {
                    $content->chunks()->delete();
                }
                $sutta->contents()->delete();
                $numDeleted++;
            }
            $this->info("Deleted $numDeleted suttas");
        }

        $domainUrl = 'http://theravada.ru';
        $suttasPath = '/Teaching/Canon/Suttanta';
        $baseUrl = $domainUrl.$suttasPath;
        $document = new Document($baseUrl.'/all-suttas-list.htm', true);
        $area = $document->find('i > a');
        foreach ($area as $node) {
            $url = $node->getAttribute('href');
            if (strpos($url, 'Texts') === false) {
                continue;
            }
            $url = \Str::start($url, '/');

            if (! str_contains($url, '/'.strtolower($category))) {
                continue;
            }
            //$this->line("$url");
            //if(!str_contains($url, "sn25_1-")) continue;

            preg_match("/\/(mn|an|sn|dn|kn)\d/m", $url, $match);
            $category = $match[1];
            $fullUrl = $baseUrl.$url;
            $this->line('');
            $this->line("download and parse $fullUrl ..");
            $import = new ImportSuttaFromTheravadaRuService(false);
            $import->fetchHtml($fullUrl);
            $theravadaRuSutta = new TheravadaruSutta();
            $theravadaRuSutta->category_name = $category;
            $theravadaRuSutta->order = $import->order();
            $theravadaRuSutta->suborder = $import->suborder();
            $theravadaRuSutta->name = $import->name();
            $theravadaRuSutta->url = $suttasPath.$url;
            $theravadaRuSutta->copyright = $import->copyright();
            $theravadaRuSutta->content = $import->content();
            $theravadaRuSutta->original_html = $import->original_html();
            $theravadaRuSutta->need_attention = $import->is_need_attention();
            if ($theravadaRuSutta->need_attention) {
                $this->error('need attention');
            }
            $this->info($theravadaRuSutta->displayIndexName().' '.$theravadaRuSutta->name);
            if (trim($theravadaRuSutta->copyright) == '') {
                $this->error('empty copyright');
            }
            $existsSutta = TheravadaruSutta::byIndexName($theravadaRuSutta->displayIndexName())->first();
            if ($existsSutta) {
                $this->error('Sutta exists. Skip.');
            } else {
                $theravadaRuSutta->save();
                $this->line('saved.');
            }

            // Сохранение в виде контента к сутте
            $suttaNameData = new SuttaNameData($theravadaRuSutta->category_name, $theravadaRuSutta->order, $theravadaRuSutta->suborder);
            $sutta = Sutta::query()
                ->bySuttaName($suttaNameData)
                ->first();
            if (! $sutta) {
                $this->error("Pali original of {$suttaNameData->name()} missing, adding SV translation is impossible");
            } else {
                $titleArray = explode(':', $theravadaRuSutta->name);
                if (count($titleArray) == 2) {
                    [$title, $subtitle] = $titleArray;
                } else {
                    $title = $theravadaRuSutta->name;
                    $subtitle = '';
                }

                /** @var Content $content */
                $content = $sutta->contents()->make();
                $content->title = $theravadaRuSutta->name;
                $content->lang = 'ru';
                $content->is_original = 0;
                $content->is_synced = 0;
                $content->translator_id = $peopleSV->id;
                $content->link_url = $fullUrl;
                $content->description = str_replace("\n", '', $import->copyright());
                $content->save();
                $this->line('Content saved.');

                $sutta->title_transcribe_ru = trim($title);
                $sutta->title_translate_ru = trim($subtitle);
                $sutta->save();

                $contentService = new ContentService($content);
                $arrayContent = explode("\n\n", $theravadaRuSutta->content);
                $contentOrder = 10;
                foreach ($arrayContent as $chunk) {
                    $contentService->addChunk($chunk, $contentOrder, null);
                    $contentOrder += 10;
                }
                $this->line('Chunks added.');

                $contentService->addExternalSource($fullUrl);
                $this->line('External source added.');
                $contentService->setMain();
                $this->line('Marked as main.');
            }
            if ($isDebug) {
                return;
            }
        }
        $this->line('done');
    }
}
