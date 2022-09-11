<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $book = new \App\Models\Book();
        $book->id = 1;
        $book->author_id = 4;
        $book->title = 'Благородный восьмеричный путь: путь к прекращению страданий';
        $book->short_description = 'Книга посвящена благородному восьмеричному пути - сердцевине учения Будды. Автор даёт подробный анализ факторов пути с цитатами из сутт Палийского канона. Также рассматривается важный вопрос необходимости духовного пути и процесс духовного поиска.';
        $book->original_title = 'The Noble Eightfold Path: The Way to the End of Suffering';
        $book->original_url = 'http://www.accesstoinsight.org/lib/authors/bodhi/waytoend.html';
        $book->slug = 'bhikkhu_bodhi_the_noble_eighfold_path';
        $book->year = '1999';
        $book->link_url = 'https://www.theravada.su/node/562';
        $book->copyright_info = '';
        $book->published_at = Carbon::now();
        $book->save();
        $bookService = new \App\Services\BookService($book);

        $enContent = $bookService->createContentFromFile('Оригинал', 'en', 'noble_eightfold_path_en.md');
        $enContent->link_url = 'http://www.accesstoinsight.org/lib/authors/bodhi/waytoend.html';
        $enContent->is_original = 1;
        $enContent->is_synced = 1;
        $enContent->save();
        $ruContent = $bookService->createContentFromFile('Перевод на русский язык: khantibalo', 'ru', 'noble_eightfold_path_ru.md');
        $ruContent->translator_name = 'khantibalo';
        $ruContent->translator_id = 2;
        $ruContent->link_url = 'https://www.theravada.su/node/562';
        $ruContent->is_main = 1;
        $ruContent->is_synced = 1;
        $ruContent->save();

        $book = new \App\Models\Book();
        $book->id = 2;
        $book->author_id = 5;
        $book->title = 'Тюрьма жизни';
        $book->short_description = '';
        $book->slug = 'ajahn_budhadasa_prison_of_life';
        $book->year = '1988';
        $book->link_url = 'http://theravada.ru/Teaching/Lectures/buddhadhasa_prison-of-life-vladislav-k.htm';
        $book->copyright_info = '';
        $book->published_at = Carbon::now();
        $book->save();
        $bookService = new \App\Services\BookService($book);
        $ruContent = $bookService->createContentFromFile('Перевод от Vladislav.K', 'ru', 'prison_of_life.md');
        $ruContent->translator_name = 'Vladislav.K (https://vladislavk.livejournal.com/34996.html)';
        $ruContent->save();
    }
}
