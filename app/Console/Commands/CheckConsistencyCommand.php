<?php

namespace App\Console\Commands;

use App\Models\Content;
use App\Models\ContentChunk;
use Illuminate\Console\Command;

class CheckConsistencyCommand extends Command
{
    protected $signature = 'lg:check_consistency {--fix}';

    protected $description = 'Проверка на несвязанные чанки и другие несоответствия в базе данных.';

    public function handle(): void
    {
        $isFix = $this->option('fix');
        $chunks = ContentChunk::query()->get();
        foreach ($chunks as $chunk) {
            if (! $chunk->content) {
                $this->error("Чанк {$chunk->id} принадлежит контенту $chunk->content_id , но такого контента нет.");
                if ($isFix) {
                    $this->info("Удаление чанка {$chunk->id}.");
                    $chunk->delete();
                }
            }
        }
        $contents = Content::query()->get();
        foreach ($contents as $content) {
            if (! $content->contentable) {
                $this->error("Контент {$content->id} принадлежит сутте {$content->contentable_type} {$content->contentable_id} , но такой сутты нет.");
                if ($isFix) {
                    $this->info("Удаление контента {$content->id}.");
                    $content->delete();
                }
            }
        }
    }
}
