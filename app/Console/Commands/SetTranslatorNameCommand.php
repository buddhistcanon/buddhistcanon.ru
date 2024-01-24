<?php

namespace App\Console\Commands;

use App\Models\Content;
use Illuminate\Console\Command;

class SetTranslatorNameCommand extends Command
{
    protected $signature = 'lb:set_translator_name';

    protected $description = 'Command description';

    public function handle(): void
    {
        $contents = Content::query()
            ->whereNotNull('translator_id')
            ->get();

        $num = 0;
        foreach ($contents as $content) {
            /** @var Content $content */
            if (! $content->translator_name) {
                try {
                    $this->line("Processing content $content->id");
                    $content->translator_name = $content->translator->displayNameRu();
                } catch (\Exception $e) {
                    dump($content);
                    $this->error($e->getMessage());

                    return;
                }

                $content->save();
                $num++;
            }
        }
        $this->line("Done, processed $num contents");
    }
}
