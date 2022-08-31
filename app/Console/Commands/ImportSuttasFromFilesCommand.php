<?php

namespace App\Console\Commands;

use App\Models\Content;
use App\Models\ContentChunk;
use App\Models\People;
use App\Models\Sutta;
use App\Services\ContentService;
use App\Services\ImportSuttaFromFileService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportSuttasFromFilesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lb:import_file_suttas {--rebuild}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import suttas from files';

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
     * @return int
     */
    public function handle()
    {
        $this->info("Import suttas from disk.");
        $disk = Storage::disk("source_suttas");
        $isRebuild = $this->option("rebuild");

        if($isRebuild){
            $this->line("Delete MN suttas..");
            $suttas = Sutta::query()
                ->where("category", "mn")
                ->with("contents")
                ->get();
            foreach($suttas as $sutta){
                foreach($sutta->contents as $content){
                    $content->chunks()->delete();
                    $content->delete();
                }
                $sutta->delete();
            }
        }

        $pathFolderPali = "pali/sutta/mn/";
        $pathFolderEn = "en/sujato/sutta/mn/";
        $missingEnSuttas = [];

        $filesPali = $disk->allFiles($pathFolderPali);

        $peopleSujato = People::where("monkname_en", "Bhante Sujato")->firstOrFail();

        foreach($filesPali as $pathFile){

            $paliSuttaService = new ImportSuttaFromFileService(str_replace($pathFolderPali, "", $pathFile), $disk->read($pathFile));
            $paliSuttaData = $paliSuttaService->getDto();

            $category = $paliSuttaData->category;
            $order = $paliSuttaData->order;
            $suborder = $paliSuttaData->suborder;
            $title = $paliSuttaData->title;
            $subtitle = $paliSuttaData->subtitle;

            //if($order!=="1") continue;

            $pathFileEn = $pathFolderEn.$paliSuttaData->category.$paliSuttaData->order.$paliSuttaData->suborder."_translation-en-sujato.json";
            try{
                $textSuttaEn = $disk->read($pathFileEn);
                $enSuttaService = new ImportSuttaFromFileService($paliSuttaData->name()."_translation-en-sujato.json", $textSuttaEn);
                $enSuttaData = $enSuttaService->getDto();
            }catch(\Exception $e){
                $this->error("EN Sujato translation not found !");
                $missingEnSuttas[] = $paliSuttaData->name();
                exit();
            }

//            $category = $this->determineCategory($pathFile);
//            $order = $this->determineOrder($pathFile);
//            $suborder = $this->determineSuborder($pathFile);
//            $title = $this->determineTitle($arrayPaliContent);
//            $subtitle = $this->determineSubtitle($arrayPaliContent);

            //dump($category, $order, $suborder, $title, $subtitle);

            DB::beginTransaction();

            $this->line($category.$order.".$suborder $title");
//            $arrayEnContent = $this->findSuttaEn($category, $order, $suborder);
//            if(!$arrayEnContent){
//                $this->error("EN Sujato translation not found !");
//                exit();
//            }

            $sutta = new Sutta();
            $sutta->category = $category;
            $sutta->order = $order;
            $sutta->suborder = $suborder;
            $name = strtoupper($category)."".$order;
            if($suborder) $name .= ".".$suborder;
            $sutta->name = $name;
            $sutta->save();

            /** @var Content $paliContent */
            $paliContent = $sutta->contents()->make();
            $paliContent->title = $title;
            $paliContent->subtitle = $subtitle;
            $paliContent->lang = "pali";
            $paliContent->is_original = 1;
            $paliContent->is_synced = "1";
            $paliContent->description = "Mahāsaṅgīti edition of the Pali Tipiṭaka";
            $paliContent->save();

            $sutta->title_pali = $paliContent->title;
            $sutta->save();

            $service = new ContentService($paliContent);
            $contentOrder = 10;
            foreach($paliSuttaData->contentWithMarks as $mark=>$chunk){
                $service->addChunk($chunk, $contentOrder, $mark);
                $contentOrder += 10;
            }
            //$paliContent = $this->addChunksToContent($arrayPaliContent, $paliContent);
            //$paliContent->save();

            /** @var Content $enContent */
            $enContent = $sutta->contents()->make();
            $enContent->title = $title;
            $enContent->subtitle = $enSuttaData->title;
            $enContent->lang = "en";
            $enContent->is_original = 0;
            $enContent->is_synced = "1";
            $enContent->description = "Translated from the Pali during 2016–2018, with reference to several English translations, especially those of Bhikkhu Bodhi";
            $enContent->translator_id = $peopleSujato->id;
            $enContent->save();

            $service = new ContentService($enContent);
            $contentOrder = 10;
            foreach($enSuttaData->contentWithMarks as $mark=>$chunk){
                $service->addChunk($chunk, $contentOrder, $mark);
                $contentOrder += 10;
            }
            //$enContent = $this->addChunksToContent($arrayEnContent, $enContent);
            //$enContent->save();

            DB::commit();
            //exit();
        }
        return 0;
    }

//    public function getFullCategory($pathFile): string
//    {
//        preg_match("/n\/(.*?)_root/", $pathFile, $match);
//        return $match[1];
//    }
//
//    private function determineCategory($pathFile): string
//    {
//        $category = $this->getFullCategory($pathFile);
//        preg_match("/([^\d]*)\d*/", $category, $match);
//        return $match[1];
//    }
//
//    public function determineOrder($pathFile): string
//    {
//        $category = $this->getFullCategory($pathFile);
//        preg_match("/[^\d]*(\d*)/", $category, $match);
//        return $match[1];
//    }
//
//    public function determineSuborder($pathFile): string|null
//    {
//        $category = $this->getFullCategory($pathFile);
//        if(!str_contains(".", $category)) return null;
//        $array = explode(".", $category);
//        return $array[1];
//    }
//
//    private function determineMark($key): string
//    {
//        preg_match("/:(.*)\./", $key, $match);
//        return $match[1];
//    }
//
//    private function determineTitle($arrayContent): string
//    {
//        return trim(
//            collect($arrayContent)->filter(function($value, $key){
//                preg_match("/.*:0\.2/", $key, $match);
//                if($match) return $value;
//            })->first()
//        );
//    }
//
//    public function determineSubtitle($arrayContent): string
//    {
//        return trim(
//            collect($arrayContent)->filter(function($value, $key){
//                preg_match("/.*:0\.(.*)/", $key, $match);
//                if(isset($match[1]) AND $match[1] != 1 AND $match[1] != 2) return $value;
//            })->reduce(function($acc, $item){
//                return $acc.$item."\n";
//            })
//        );
//    }

//    public function findSuttaEn($category, $order, $suborder): array|false
//    {
//        $disk = Storage::disk("source_suttas");
//        $pathFolderEn = "en/sujato/sutta/mn/";
//
//        $path = $pathFolderEn."/".$category.$order.$suborder."_translation-en-sujato.json";
//        $file = $disk->read($path);
//        if(!$file) return false;
//
//        return json_decode($file, true);
//    }

//    public function addChunksToContent($arrayContent, Content $content): Content
//    {
//        $text = "";
//        $mark = "";
//        $currentMark = "";
//        $service = new ContentService($content);
//        foreach($arrayContent as $key=>$value){
//            $currentMark = $this->determineMark($key);
//            if($currentMark == 0)  continue;
//            if(!$mark) $mark = $currentMark;
//            if($mark === $currentMark){
//                $text .= trim($value) . "\n";
//            }else{
//                // создаём новый чанк
////                $this->info(trim($text));
//                $service->addChunk(trim($text), null, $mark);
//                $text = "";
//                $mark = $currentMark;
//            }
//        }
//        return $service->getContent();
//    }

}
