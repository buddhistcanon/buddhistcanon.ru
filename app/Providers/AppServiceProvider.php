<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\Content;
use App\Models\ExternalSource;
use App\Models\Sutta;
use App\Models\Term;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use MeiliSearch\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        // Laravel Scout не умеет делать фильтры, ставим их вручную
//        $client = new Client(config("scout.meilisearch.host"), config("scout.meilisearch.key"));
//        $client->index("content_chunks")->updateFilterableAttributes(['content_type']);

        // All future morphs *must* be mapped!
        Relation::enforceMorphMap([
            'sutta' => Sutta::class,
            'book' => Book::class,
            'term' => Term::class,
            'source' => ExternalSource::class,
            'user' => User::class,
            'content' => Content::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
