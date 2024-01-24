<?php

namespace App\Console\Commands;

use App\Models\Content;
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
    protected $signature = 'lb:import_file_suttas {category} {--subfolders=} {--rebuild}';

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
        $this->info('Import suttas from disk.');
        $disk = Storage::disk('source_suttas');
        $isRebuild = $this->option('rebuild');
        $category = strtolower($this->argument('category'));
        $maxSubfolders = 1;

        if ($category === 'an') {
            $maxSubfolders = 10;
        }
        if ($category === 'sn') {
            $maxSubfolders = 56;
        }

        if ($isRebuild) {
            $this->line('Delete $category suttas..');
            $suttas = Sutta::query()
                ->where('category', $category)
                ->with('contents')
                ->get();
            foreach ($suttas as $sutta) {
                foreach ($sutta->contents as $content) {
                    $content->chunks()->delete();
                    $content->delete();
                }
                $sutta->delete();
            }
        }

        $pathFolderPaliParent = "pali/sutta/$category/";
        $pathFolderEnParent = "en/sujato/sutta/$category/";
        $missingEnSuttas = [];

        for ($i = 1; $i <= $maxSubfolders; $i++) {

            $folder = $category.$i;
            if ($category == 'mn') {
                $folder = '';
            }
            $pathFolderEn = $pathFolderEnParent.$folder.'/';
            $pathFolderPali = $pathFolderPaliParent.$folder.'/';

            $filesPali = $disk->allFiles($pathFolderPali);

            $peopleSujato = People::where('monkname_en', 'Bhante Sujato')->firstOrFail();

            foreach ($filesPali as $pathFile) {

                //                dump($pathFile);
                //                preg_match("/\/$folder\.\d+-\d+_root/", $pathFile, $match);
                //                dd($match);

                $paliSuttaService = new ImportSuttaFromFileService(str_replace($pathFolderPali, '', $pathFile), $disk->read($pathFile));
                $paliSuttaData = $paliSuttaService->getDto();

                $category = $paliSuttaData->category;
                $order = $paliSuttaData->order;
                $suborder = $paliSuttaData->suborder;
                $title = $paliSuttaData->title;
                $subtitle = $paliSuttaData->subtitle;

                //if($order!=="1") continue;

                $pathFileEn = $pathFolderEn.$paliSuttaData->category.$paliSuttaData->order.'.'.$paliSuttaData->suborder.'_translation-en-sujato.json';
                try {
                    $textSuttaEn = $disk->read($pathFileEn);
                    $enSuttaService = new ImportSuttaFromFileService($paliSuttaData->name().'_translation-en-sujato.json', $textSuttaEn);
                    $enSuttaData = $enSuttaService->getDto();
                } catch (\Exception $e) {
                    $this->error("EN Sujato translation for {$paliSuttaData->name()} not found ! $pathFileEn");
                    $missingEnSuttas[] = $paliSuttaData->name();
                    exit();
                }

                DB::beginTransaction();

                $this->line($category.$order.".$suborder $title");

                $sutta = new Sutta();
                $sutta->category = $category;
                $sutta->order = $order;
                $sutta->suborder = $suborder;
                $name = strtoupper($category).''.$order;
                if ($suborder) {
                    $name .= '.'.$suborder;
                }
                $sutta->name = $name;
                $sutta->save();

                /** @var Content $paliContent */
                $paliContent = $sutta->contents()->make();
                $paliContent->title = $title;
                $paliContent->subtitle = $subtitle;
                $paliContent->lang = 'pali';
                $paliContent->is_original = 1;
                $paliContent->is_synced = '1';
                $paliContent->description = 'Mahāsaṅgīti edition of the Pali Tipiṭaka';
                $paliContent->short_description = 'Оригинал на пали, редакция Mahāsaṅgīti';
                $paliContent->save();

                $sutta->title_pali = $paliContent->title;
                $sutta->save();

                $service = new ContentService($paliContent);
                $contentOrder = 10;
                foreach ($paliSuttaData->contentWithMarks as $mark => $chunk) {
                    $service->addChunk($chunk, $contentOrder, $mark);
                    $contentOrder += 10;
                }

                /** @var Content $enContent */
                $enContent = $sutta->contents()->make();
                $enContent->title = $title;
                $enContent->subtitle = $enSuttaData->title;
                $enContent->lang = 'en';
                $enContent->is_original = 0;
                $enContent->is_synced = '1';
                $enContent->description = 'Translated from the Pali during 2016–2018, with reference to several English translations, especially those of Bhikkhu Bodhi';
                $enContent->short_description = 'Перевод c пали на английский, Бханте Суджато';
                $enContent->translator_id = $peopleSujato->id;
                $enContent->save();

                $service = new ContentService($enContent);
                $contentOrder = 10;
                foreach ($enSuttaData->contentWithMarks as $mark => $chunk) {
                    $service->addChunk($chunk, $contentOrder, $mark);
                    $contentOrder += 10;
                }

                DB::commit();
            }
        }

    }
}
