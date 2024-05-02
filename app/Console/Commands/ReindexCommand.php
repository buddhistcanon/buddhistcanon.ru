<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'lg:reindex';

    protected $description = 'Delete and rebuild search index';

    public function handle(): void
    {
        $this->info('Rebuilding search index...');

        $this->call('scout:flush', ['model' => 'App\Models\ContentChunk']);
        $this->call('scout:import', ['model' => 'App\Models\ContentChunk']);

        $this->info('Search index rebuilt!');
    }
}
