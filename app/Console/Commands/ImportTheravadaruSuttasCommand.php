<?php

namespace App\Console\Commands;

use App\Data\SuttaData;
use App\Data\SuttaNameData;
use App\Models\Content;
use App\Models\ContentChunk;
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
    protected $signature = 'lb:import_theravadaru_suttas {--rebuild} {--debug}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //exit("Please check code");
        $isRebuild = $this->option("rebuild");
        $isDebug = $this->option("debug");

        $peopleSV = People::where("nickname", "SV")->firstOrFail();

        if($isRebuild){
            $this->info("Delete all suttas");
            DB::statement("TRUNCATE TABLE theravadaru_suttas");
            DB::statement("ALTER TABLE theravadaru_suttas AUTO_INCREMENT=1");
            $contents = Content::query()
                ->where("translator_id", $peopleSV->id)
                ->where("contentable_type", "sutta")
                ->with("external_sources")
                ->get();
            foreach($contents as $content){
                $content->external_sources()->delete();
                $content->delete();
            }
        }

        $domainUrl = "http://theravada.ru";
        $suttasPath = "/Teaching/Canon/Suttanta";
        $baseUrl = $domainUrl.$suttasPath;
        $document = new Document($baseUrl.'/all-suttas-list.htm', true);
        $area = $document->find("i > a");
        foreach($area as $node){

            $url = $node->getAttribute("href");
            if(strpos($url, "Texts")===false) continue;
            $url = \Str::start($url, "/");

            if(!str_contains($url, "/mn")) continue;
            //$this->line("$url");
            //if(!str_contains($url, "sn25_1-")) continue;

            preg_match("/\/(mn|an|sn|dn|kn)\d/m", $url, $match);
            $category = $match[1];

            $fullUrl = $baseUrl.$url;
            $this->line("");
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
            if($theravadaRuSutta->need_attention){
                $this->error("need attention");
            }
            $this->info($theravadaRuSutta->displayIndexName()." ".$theravadaRuSutta->name);
            if(trim($theravadaRuSutta->copyright) == "") $this->error("empty copyright");
            $existsSutta = TheravadaruSutta::byIndexName($theravadaRuSutta->displayIndexName())->first();
            if($existsSutta){
                $this->error("Sutta exists. Skip.");
            }else{
                $theravadaRuSutta->save();
                $this->line("saved.");
            }

            // Сохранение в виде контента к сутте
            $suttaNameData = new SuttaNameData($theravadaRuSutta->category_name, $theravadaRuSutta->order, $theravadaRuSutta->suborder);
            $sutta = Sutta::query()
                ->bySuttaName($suttaNameData)
                ->first();
            if(!$sutta){
                $this->error("{$suttaNameData->name()} missing, adding SV translation is impossible");
            }else{

                $titleArray = explode(":", $theravadaRuSutta->name);
                if(count($titleArray) == 2){
                    [$title, $subtitle] = $titleArray;
                }else{
                    $title = $theravadaRuSutta->name;
                    $subtitle = "";
                }

                /** @var Content $content */
                $content = $sutta->contents()->make();
                $content->title = $theravadaRuSutta->name;
                $content->lang = "ru";
                $content->is_original = 0;
                $content->is_synced = 0;
                $content->translator_id = $peopleSV->id;
                $content->link_url = $fullUrl;
                $content->description = str_replace("\n", "", $import->copyright());
                $content->save();
                $this->line("Content saved.");

                $sutta->title_transcribe_ru = trim($title);
                $sutta->title_translate_ru = trim($subtitle);
                $sutta->save();

                $contentService = new ContentService($content);
                $arrayContent = explode("\n\n", $theravadaRuSutta->content);
                $contentOrder = 10;
                foreach($arrayContent as $chunk){
                    $contentService->addChunk($chunk, $contentOrder, null);
                    $contentOrder += 10;
                }
                $this->line("Chunks added.");

                $contentService->addExternalSource($fullUrl);
                $this->line("External source added.");
                $contentService->setMain();
                $this->line("Marked as main.");

            }
        }
        $this->line("done");
    }
}
