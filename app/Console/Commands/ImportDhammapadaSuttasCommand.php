<?php

namespace App\Console\Commands;

use App\Models\Content;
use App\Models\Sutta;
use App\Services\ContentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportDhammapadaSuttasCommand extends Command
{
    protected $signature = 'bc:import_dhammapada_suttas {--rebuild}';

    protected $description = 'Import Dhammapada suttas in pali from files';

    public function handle(): void
    {
        $disk = Storage::disk('source_suttas');
        $isRebuild = $this->option('rebuild');

        $category = 'dhp';

        if ($isRebuild) {
            $this->line("Delete $category suttas..");
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

        $folder = 'pali/sutta/kn/dhp';
        $files = $disk->allFiles($folder);
        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $suttaName = str_replace('_root-pli-ms', '', $filename);
            $this->info($suttaName);
            $content = $disk->get($file);
            $data = json_decode($content, true);
            $suttaName = '';
            $paragraph = 0;
            $prevSuttaName = '';
            $title = '';
            $lines = [];

            foreach ($data as $key => $value) {
                [$suttaName, $paragraphLine] = explode(':', $key);
                if (str_contains($paragraphLine, '.')) {
                    [$paragraph, $mark] = explode('.', $paragraphLine);
                } else {
                    $paragraph = $paragraphLine;
                    $mark = null;
                }
                if ($prevSuttaName !== $suttaName) {
                    $this->line($prevSuttaName);
                    $order = str_replace('dhp', '', $prevSuttaName);
                    $name = 'Dhammapada '.$order;
                    $existSutta = Sutta::query()
                        ->where('category', $category)
                        ->where('order', $order)
                        ->first();
                    if (! $existSutta and count($lines) > 0) {
                        $this->line("Add sutta $name to database");
                        $this->_save($category, $order, $name, $title, $lines);
                    }
                    $lines = [];
                    if ($paragraph == '0' and $mark == null) {
                        $title = $value;
                    } else {
                        $lines[] = $value;
                    }
                } else {
                    if ($paragraph == '0' and $mark == null) {
                        $title = $value;
                    }
                    if ($mark == null) {
                        // если не заголовок сутты - добавляем в контент
                        $lines[] = $value;
                    }
                }
                $prevSuttaName = $suttaName;
            }
        }

    }

    private function _save(string $category, int $order, string $suttaName, string $contentTitle, array $lines): void
    {
        DB::beginTransaction();

        $sutta = new Sutta();
        $sutta->category = $category;
        $sutta->order = $order;
        $sutta->suborder = null;
        $sutta->name = $suttaName;
        $sutta->save();

        /** @var Content $paliContent */
        $paliContent = $sutta->contents()->make();
        $paliContent->title = $contentTitle;
        $paliContent->subtitle = null;
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
        $chunk = collect($lines)->reduce(function ($acc, $item) {
            return $acc.$item."\n";
        });
        $mark = Str::random(5);
        $service->addChunk($chunk, $contentOrder, $mark);

        $paliContent->fresh();
        dump($paliContent->chunks->toArray());
        DB::commit();
    }
}
