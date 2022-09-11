<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInitialStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->string('full_name');
            $table->string('nickname');
            $table->string('slug')->index();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('priority')->default(0);
            $table->timestamps();
        });

        // Crawler - скачивание всех сайтов целиком при помощи spatie/crawler

        Schema::create('crawler_projects', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->index();
            $table->string('root_url');
            $table->string('start_url');
            $table->json('exclude_patterns')->nullable();
            $table->bigInteger('session')->default(1)->index();
            $table->integer('deep')->default(0);
            $table->timestamp('crawled_at')->nullable();
        });

        Schema::create('crawler_pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('project_id')->index();
            $table->string('url')->unique();
            $table->string('status');
            $table->longText('content')->nullable();
            $table->string('content_hash')->nullable();
            $table->timestamp('hashed_at')->nullable();
            $table->longText('old_content')->nullable();
            $table->bigInteger('session')->default(1)->index();
            $table->timestamp('checked_at')->nullable();
        });

        Schema::create('crawler_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('project_id');
            $table->string('url');
            $table->longText('old_content')->nullable();
            $table->longText('new_content');
            $table->timestamp('processed_at')->nullable(); // изменения по этим добавлениям внесены на сайте
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('content_chunk_id')->nullable()->index();
            $table->string('mark')->nullable()->index();
            $table->longText('text');
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(); // book title or suttas title
            $table->text('subtitle')->nullable(); // sutta subtitle
            $table->string('lang'); // pali, en, ru
            $table->string('translator_name')->nullable();
            $table->unsignedBigInteger('translator_id')->nullable(); // from peoples
            $table->string('link_url')->nullable(); // с какого ресурса взято - for display only
            $table->longText('description')->nullable(); // копирайтная информация, переводчик, оригинал, ссылки и т.п.
            $table->longText('table_of_contents')->nullable(); // Предусмотреть заполнение автоматом из h2 заголовков
            $table->morphs('contentable');
            $table->boolean('is_main')->default(0);
            $table->boolean('is_original')->default(0); // оригинальный текст, с которого в других контентах делался перевод
            $table->string('is_synced')->default(1); // контент связан поабзацтно с другими контентами с is_synced
            // есть несколько external_sources через externable
            $table->timestamps();
        });

        Schema::create('content_chunks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->index();
//            $table->tinyInteger("content_type"); // ContentChunk::TYPE_BOOK или ContentChunk::TYPE_SUTTA
            $table->morphs('chunkable');
            $table->bigInteger('order'); // порядок следования чанков
            $table->longText('text')->nullable();
            $table->string('mark')->nullable(); // метки абзацев для сутт с suttacentral ( SC3 и т.п.)
            $table->timestamps();
        });

        // Мониторинг изменений контента внешних источников
        Schema::create('external_sources', function (Blueprint $table) {
            $table->id();
            $table->string('url')->index();
            $table->string('name')->nullable();
            $table->morphs('externable');
            $table->longText('content')->nullable();
            $table->string('content_hash')->nullable();
            $table->timestamp('hashed_at')->nullable();
            $table->timestamp('checked_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('external_source_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('external_source_id')->index();
            $table->longText('old_content')->nullable();
            $table->longText('new_content')->nullable();
            $table->timestamp('processed_at')->nullable(); // изменения по этим добавлениям внесены на сайте
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->index(); // автор - people
            $table->string('title');
            $table->string('slug')->index();
            $table->text('short_description'); // Информация о книге, короткая, выводится в списке книг
            $table->text('description')->nullable(); // Информация о книге, развёрнутая, возможно, со ссылками на сайт, откуда книга взята
            $table->boolean('is_short')->default(0)->index(); // это короткое эссе ? TODO подумать, нужно ли
            $table->string('original_title')->nullable();
            $table->string('original_url')->nullable();
            $table->string('year')->nullable(); // год, в котором написана книга
            $table->text('copyright_info')->nullable();
            $table->string('link_url')->nullable(); // с какого ресурса взято
            $table->timestamp('published_at')->nullable(); // draft
            $table->boolean('is_copyrighted')->default(0);
            $table->text('buy_urls')->nullable(); // где можно купить
            $table->string('image')->nullable(); // картинка обложки
            $table->integer('part')->default(1)->index(); // номера частей книги - части книги пока делаем так
            $table->integer('total_parts')->default(1)->index(); // количество частей у книги
            $table->unsignedBigInteger('firstpart_book_id')->nullable(); // id первой части
            // у book есть несколько content через contentable
            // у book есть несколько content_chunks через chunkable, но они используются только от ContentChunk при поиске
            $table->timestamps();
        });

        Schema::create('dictionaries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('dictionaries_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dictionary_id')->index();
            $table->string('entry')->index();
            $table->string('from');
            $table->string('to');
            $table->string('grammar');
            $table->text('definition');
            $table->bigInteger('session')->default(1)->index();
            $table->timestamps();
        });

        Schema::create('email_subscribes', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('content_chunk_id')->index();
            $table->longText('description')->nullable();
            $table->longText('text')->nullable();
            $table->timestamps();
        });

        Schema::create('logs_search', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('ip')->index();
            $table->string('api_key')->nullable();
            $table->string('search');
            $table->longText('result');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('text')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('on_route')->nullable(); // на каком роуте оно находится - сделать автозаполнение при первом вызове
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('peoples', function (Blueprint $table) {
            $table->id();
            $table->string('fullname_ru')->nullable();
            $table->string('monkname_ru')->nullable();
            $table->string('fullname_en')->nullable();
            $table->string('monkname_en')->nullable();
            $table->string('nickname')->nullable();
            $table->string('slug')->index();
            $table->boolean('is_monk')->default(0);
            $table->unsignedBigInteger('priority')->default(0);
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        // TODO ещё раз подумать над линковкой
        // Таблица учёта линковки терминов и контента книг
        Schema::create('references_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('term_id')->index();
            $table->unsignedBigInteger('content_chunk_id')->index();
        });
        // Таблица учёта линковки сутт и контента книг
        Schema::create('references_suttas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sutta_id')->index();
            $table->unsignedBigInteger('content_chunk_id')->index();
        });

        Schema::create('search_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token')->index();
            $table->boolean('is_active')->default(1);
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('suttas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index(); // SN43.45
            $table->string('category'); // mn, dn
            $table->integer('order'); // 1, 2
            $table->string('suborder')->nullable(); // 12, 13
            $table->string('title_pali')->nullable(); // Cūḷapuṇṇamasutta
            $table->string('title_transcribe_ru')->nullable(); // Чулапуннама-сутта
            $table->string('title_translate_ru')->nullable(); // Малое наставление в ночь полной луны
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->index(['category', 'order']);
            // у cутты есть несколько content через contentable
            // у сутты есть несколько content_chunks через chunkable, но они используются только от ContentChunk при поиске
        });

        Schema::create('terms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->index();
            $table->text('short_text'); // короткое описание термина, на попапе
            $table->text('parts_text')->nullable(); // список терминов, который находится внутри этого термина (у Б8П тут будет список из 8 пунктов). формат текста - md
            $table->unsignedBigInteger('parent_term_id')->nullable(); // id термина, к которому принадлежит этот термин, у которого он входит в parts
            $table->longText('text')->nullable(); // полный текст описания термина
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('term_variants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('term_id')->index();
            $table->string('title')->index();
            $table->boolean('is_main')->default(0);
            $table->timestamps();
        });

        Schema::create('term_proposals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->index();
            $table->unsignedBigInteger('content_chunk_id')->nullable(); // там, где найден этот нераспределённый термин
            $table->unsignedBigInteger('term_id')->nullable();
            $table->timestamps();
        });

        Schema::create('theravadaru_suttas', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->index(); // mn, dn
            $table->string('order'); // 1, 2
            $table->string('suborder')->nullable(); // 12, 13
            $table->string('name');
            $table->string('url');
            $table->text('copyright')->nullable();
            $table->longText('content');
            $table->longText('original_html');
            $table->tinyInteger('need_attention');
            $table->timestamps();
        });

        Schema::create('translators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index()->nullable();
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('slug')->index();
            $table->longText('description')->nullable();
            $table->timestamps();
        });

        Schema::create('user_book_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('book_id')->index();
            $table->boolean('is_allow')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('authors');
        Schema::dropIfExists('books');
        Schema::dropIfExists('crawler_projects');
        Schema::dropIfExists('crawler_pages');
        Schema::dropIfExists('crawler_logs');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('content_threads');
        Schema::dropIfExists('content_chunks');
        Schema::dropIfExists('dictionaries');
        Schema::dropIfExists('dictionaries_terms');
        Schema::dropIfExists('email_subscribes');
        Schema::dropIfExists('external_sources');
        Schema::dropIfExists('external_source_logs');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('logs_search');
        Schema::dropIfExists('peoples');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('term_descriptions');
        Schema::dropIfExists('term_variants');
        Schema::dropIfExists('term_proposals');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('references_terms');
        Schema::dropIfExists('references_suttas');
        Schema::dropIfExists('theravadaru_suttas');
        Schema::dropIfExists('translators');
        Schema::dropIfExists('search_tokens');
        Schema::dropIfExists('suttas');
        Schema::dropIfExists('user_book_access');
    }
}
